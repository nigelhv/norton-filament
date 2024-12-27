<?php

namespace App\Models;

use Filament\Forms;
use App\Models\MyDate;
use App\Models\Activity;
use App\Models\Scopes\StudentScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope(new StudentScope);
    }

    protected $fillable = ['on_roll'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->surname);
    }

    protected static function boot()
    {
        parent::boot();
    }
    public function fullName()
    {
        return $this->first_name . ' ' . $this->surname;
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('surname')->orderBy('first_name');
    }



    public function scopeWithTotalActivitiesCount($query)
    {
        $start_date = (new MyDate)->yearStartDate();
        $end_date = new \Carbon\Carbon;
        $subQuery = \DB::table('activity_student')
            ->selectRaw('count(*) as total_activities')
            ->whereRaw('student_id = students.id');

        return $query->select('students.*')->selectSub($subQuery, 'total_activities');

        // return \DB::table('students')
        //    ->select()
        //    ->selectSub('select count(*) from activity_student where student_id = id', 'total_activities');
    }

    public function scopeWithMondayActivitiesCount($query)
    {
        $monday = (new MyDate())->thisMonday();
        $subQuery = \DB::table('activity_student')
            ->selectRaw('count(*) as total_activities')
            ->whereRaw('student_id = students.id');

        return $query->select('students.*')->selectSub($subQuery, 'total_activities');
    }


    public function activities()
    {
        return $this->belongsToMany(Activity::class)
            ->orderBy('date', 'desc')
            ->withPivot('notes');
    }



    public function thisWeeksActivities($date = null)
    {
        if (is_null($date)) {
            $date = today();
        }
        $date = new MyDate($date);
        $start_date = $date->thisMonday();
        $end_date = $date->thisFriday();
        $activities = $this->activities;
        return $activities->filter(function ($activity) use ($start_date, $end_date) {
            return $activity->date >= $start_date && $activity->date <= $end_date;
        });
    }
    // public function delWeeklyActivitiesCount()
    // {
    //     $date=new MyDate();
    //     $start_date=$date->thisMonday();
    //     $end_date=$date->thisFriday();

    //     $activities=$this->activities;
    //     return $activities->filter(function($activity) use($start_date, $end_date){
    //             return $activity->date->toDateString() >= $start_date && $activity->date->toDateString() <= $end_date;
    //     });
    // }

    public function activitiesWithFilter($start_date, $end_date, $subject_filter = null)
    {
        // $start_date = (new MyDate)->yearStartDate();
        // $end_date = new \Carbon\Carbon;

        $activities = $this->activities()
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->orderBy('date', 'asc')
            ->with(['users', 'subjects'])
            ->get();

        if ($subject_filter == null) {
            return $activities;
        }

        $filtered_activities = collect();
        $filtered_activities = $activities->filter(function ($activity) use ($subject_filter) {
            return $activity->hasSubject($subject_filter);
        });

        return $filtered_activities;
    }

    public function uniqueSubjects()
    {

        return $this->activities
            ->map(function ($activity) {
                return $activity->subjects;
            })
            ->flatten()
            ->unique('id')
            ->sortBy('name');
    }


    public function subjects()
    {
        $subjects = [];
        foreach ($this->activities as $activity) {
            foreach ($activity->subjects as $subject) {
                $subjects[] = $subject->id;
            }
        }
        return array_unique($subjects);
    }

    public function allSubjects()
    {
        $subjects = [];
        foreach ($this->activities as $activity) {
            foreach ($activity->subjects as $subject) {
                $subjects[] = $subject->id;
            }
        }
        return array_unique($subjects);
    }

    public function countThisWeeks($subject)
    {
        $activities = Activity::whereHas('subjects', function ($query) {
            $query->where('name', 'like', 'english');
        })->get();
    }

    // public function countThisWeeksActivities($date)
    // {
    //     $date_calculator=new MyDate($date);
    //     $start_date=$date_calculator->thisMonday();
    //     $end_date=$date_calculator->thisFriday();

    //     return \Cache::rememberForever($this->id.':weekly:'.$start_date.':activities', function() use($start_date, $end_date){
    //         return $this->activities->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
    //     });
    // }

    // public function didActivityOn($date)
    // {
    //     $activities=$this->activities()->where('date', $date)->count();
    //     if($activities > 0)
    //     {
    //         return $activities;
    //     }
    //     return false;
    // }

    public function countActivitiesOn($date)
    {
        return \Cache::rememberForever($this->id . ':date:' . $date . ':activities', function () use ($date) {
            return $activities = $this->activities()->where('date', $date)->count();
        });
    }

    // public function activityDate()
    // {
    //     $date=new MyDate();
    //     return $date;
    // }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


    public function wasPresentOn($date)
    {
        $attendances = $this->attendances()->where('date', $date)->count();
        if ($attendances > 0) {
            return true;
        }
        return false;
    }

    public function attendanceCodeOn($date)
    {
        if ($this->attendances()->where('date', $date)->first() !== null) {
            return $this->attendances()->where('date', $date)->first()->attendance_code->code;
        }
        return '';
    }

    public function attendanceColourOn($date)
    {
        // 400 queries
        return '#CBD2D9'; // remove this
        if ($this->attendances()->where('date', $date)->first() !== null) {
            return $this->attendances()->where('date', $date)->first()->attendance_code->colour;
        }
        return '#CBD2D9';
    }

    public function attendanceRecordOn($date)
    {
        // 400 queries
        return ''; // remove this
        if ($this->attendances()->where('date', $date)->first() !== null) {
            return $this->attendances()->where('date', $date)->first();
        }
        return '';
    }

    public function attendancesThisWeek($date = null)
    {
        $attendances = Attendance::where('student_id', $this->id)
            ->where('date', '>=', (new MyDate($date))
                ->thisMonday())
            ->where('date', '<=', (new MyDate($date))
                ->thisFriday())
            ->get();
        return $attendances->sum('value');
    }

    public function totalAttendances($date = null)
    {
        if ($date == null) {
            $date = (new MyDate)->yearStartDate();
            // $date = '2018-09-01';
        }
        return Attendance::where('student_id', $this->id)
            ->where('date', '>=', $date)
            ->sum('value');
    }

    public function totalAttendanceRecords($date = null)
    {
        // return 99; // remove this
        // this neeeds to be fixed for future years!
        if ($date == null) {
            $date = '2018-09-01';
        }
        return Attendance::where('student_id', $this->id)
            ->where('date', '>=', $date)
            ->count();
    }

    public function attendanceEntriesThisWeek($date = null)
    {
        $attendances = Attendance::where('student_id', $this->id)
            ->where('date', '>=', (new MyDate($date))
                ->thisMonday())
            ->where('date', '<=', (new MyDate($date))
                ->thisFriday())
            ->get();
        return $attendances->count();
    }

    public function countAttendanceType($code_id, $daterange)
    {
        return \Redis::get('attendance:' . $this->id . ':' . $code_id);

        // Ignore date range for now :-)
        // $start_date='2018-09-01';
        // $end_date=today()->toDateString();
        // return Attendance::where('student_id', $this->id)
        //     ->where('date', '>=', $start_date)
        //     ->where('date', '<=', $end_date)
        //     ->where('attendance_code_id', $code_id)
        //     ->count();
    }

    public function countAttendanceTypeFromDatabase($code_id, $daterange)
    {
        // return \Redis::get('attendance:'.$this->id.':'.$code_id);

        // Ignore date range for now :-)
        $start_date = '2018-09-01';
        $end_date = today()->toDateString();
        return Attendance::where('student_id', $this->id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('attendance_code_id', $code_id)
            ->count();
    }

    public function refreshRedisAttendanceStats($code_id, $daterange)
    {
        $count = $this->countAttendanceTypeFromDatabase($code_id, $daterange);
        \Redis::set('attendance:' . $this->id . ':' . $code_id, $count);
        return $count;
    }

    public function incActivitiesCount()
    {
        return \Cache::increment($this->id . ':activities');
    }

    public function incDailyActivitiesCount($date)
    {
        if (!is_string($date)) {
            $date = $date->toDateString();
        }

        return \Cache::increment($this->id . ':' . $date . ':activities');
    }

    public function incWeeklyActivitiesCount($weekbeginning)
    {
        if (!is_string($weekbeginning)) {
            $weekbeginning = $weekbeginning->toDateString();
        }
        return \Cache::increment($this->activitiesInAWeekKey($weekbeginning));
    }

    public function getActivitiesCount()
    {
        $start_date = (new MyDate)->yearStartDate();
        $end_date = new \Carbon\Carbon;
        // return \Cache::get($this->id.':activities');
        return \Cache::rememberForever($this->id . ':activities', function () use ($start_date, $end_date) {
            return $this->activitiesWithFilter($start_date, $end_date)->count();
        });
    }

    public function decActivitiesCount()
    {
        return \Cache::decrement($this->id . ':activities');
    }

    public function delActivitiesCount()
    {
        return \Cache::forget($this->id . ':activities');
    }

    public function delDailyActivitiesCountOn($date)
    {
        return \Cache::forget($this->id . ':date:' . $date . ':activities');
    }

    public function delWeeklyActivitiesCount($date = null)
    {
        if (is_null($date)) {
            $date = today();
        }
        $weekbeginning = (new MyDate($date))->thisMonday($date);
        return \Cache::forget($this->activitiesInAWeekKey($weekbeginning));
    }

    public function getWeeklyActivitiesCount($date = null)
    {
        if (is_null($date)) {
            $date = today();
        }
        $weekbeginning = (new MyDate($date))->thisMonday();
        return \Cache::rememberForever($this->activitiesInAWeekKey($weekbeginning), function () {
            return $this->thisWeeksActivities()->count();
        });
    }

    // Extra credit activities
    public function thisWeeksEcActivities($date = null)
    {
        if (is_null($date)) {
            $date = today();
        }
        $date = new MyDate($date);
        $start_date = $date->thisMonday();
        $end_date = $date->thisFriday();
        $activities = $this->activities;
        return $activities->filter(function ($activity) use ($start_date, $end_date) {
            return $activity->date->toDateString() >= $start_date && $activity->date->toDateString() <= $end_date;
        });
    }


    public function getWeeklyEcActivitiesCount($date = null)
    {
        if (is_null($date)) {
            $date = today();
        }
        $weekbeginning = (new myDate($date))->thisMonday();
        return \Cache::rememberForever($this->id . ':weekly:' . $weekbeginning . ':ecactivities', function () {
            return $this->thisWeeksEcActivities()->count();
        });
    }

    public function getActivitiesOn($date)
    {
        $activities = $this->activities()->get();
        return $activities->filter(function ($activity) use ($date) {
            return $activity->date == $date;
        });
    }
    public function getActivitiesCountOn($date)
    {
        return \Cache::rememberForever($this->id . ':' . $date . ':activities', function () use ($date) {
            return $this->getActivitiesOn($date)->count();
        });
    }
    public function delWeeksDailyActivitiesCounts($date)
    {
        $mydate = new MyDate();
        $start_date = $mydate->thisMonday($date);
        $end_date = $mydate->thisFriday($date);
        $sequence = $mydate->dateSequence($start_date, $end_date);
        foreach ($sequence as $date) {
            $this->delDailyActivitiesCountOn($date);
        }
        return $sequence;
    }

    public function getWeeklyMecCount()
    {
        $date = new MyDate();
        $weekbeginning = $date->thisMonday();
        $value = \Cache::rememberForever($this->id . ':weekly:' . $weekbeginning . ':mec', function () {
            return $this->thisWeeksActivities()->sum->maths_extra_credit;
        });
        return $value;
    }

    public function getWeeklyEecCount()
    {
        $date = new MyDate();
        $weekbeginning = $date->thisMonday();
        $value = \Cache::rememberForever($this->id . ':weekly:' . $weekbeginning . ':eec', function () {
            return $this->thisWeeksActivities()->sum->english_extra_credit;
        });
        return $value;
    }

    public function delWeeklyEecCount()
    {
        $date = new MyDate();
        $weekbeginning = $date->thisMonday();
        \Cache::forget($this->id . ':weekly:' . $weekbeginning . ':eec');
    }

    public function delWeeklyMecCount()
    {
        $date = new MyDate();
        $weekbeginning = $date->thisMonday();
        \Cache::forget($this->id . ':weekly:' . $weekbeginning . ':mec');
    }

    public function recalcWeeklyExtraCredit()
    {
        $this->delWeeklyMecCount();
        $this->delWeeklyEecCount();
        $this->getWeeklyEecCount();
        $this->getWeeklyMecCount();
    }

    public function recalcWeeklyActivities()
    {
        $this->delWeeklyActivitiesCount();
        $this->getWeeklyActivitiesCount();
    }

    public function recalcMondayActivities($date)
    {
        $monday = (new MyDate($date))->thisMonday();
        $this->delDailyActivitiesCountOn($monday);
        $this->countActivitiesOn($monday);
    }



    public function incWeeklyEecCount($weekbeginning, $inc = null)
    {
        $monday = (new MyDate($weekbeginning))->thisMonday();
        if (!$inc) {
            $inc = 1;
        }
        return \Cache::increment($this->id . ':weekly:' . $monday . ':eec', $inc);
    }

    public function incWeeklyMecCount($weekbeginning, $inc = null)
    {
        $monday = (new MyDate($weekbeginning))->thisMonday();
        if (!$inc) {
            $inc = 1;
        }
        return \Cache::increment($this->id . ':weekly:' . $monday . ':mec', $inc);
    }

    public function isSuccessful()
    {
        $credits = $this->getWeeklyEecCount()
            + $this->getWeeklyMecCount()
            + $this->thisWeeksActivities()->count();
        return $credits >= 4;
    }

    public function meetsThisWeeksTarget()
    {
        $total_activities = $this->getWeeklyActivitiesCount();
        $total_english_extra_credit = $this->getWeeklyEecCount();
        $total_maths_extra_credit = $this->getWeeklyMecCount();
        $target = \Config::get('constants.options.activities_target');
        if ($total_activities + $total_english_extra_credit + $total_maths_extra_credit >= $target) {
            return true;
        }
        return false;
    }

    // Generate Redis keys
    public function activitiesOnADayKey($date = null)
    {
        if (is_null($date)) {
            $date = today()->toDateString();;
        }
        return $this->id . ':' . $date . ':activities';
    }
    public function activitiesInAWeekKey($date = null)
    {
        if (is_null($date)) {
            $date = today()->toDateString();
        }
        $weekbeginning = (new myDate($date))->thisMonday($date);
        return $this->id . ':weekly:' . $weekbeginning . ':activities';
    }

    public function eecInAWeekKey($date = null)
    {
        if (is_null($date)) {
            $date = today()->toDateString();
        }
        $weekbeginning = (new myDate($date))->thisMonday($date);
        return $this->id . ':weekly:' . $weekbeginning . ':eec';
    }

    public function mecInAWeekKey($date = null)
    {
        if (is_null($date)) {
            $date = today()->toDateString();
        }
        $weekbeginning = (new myDate($date))->thisMonday($date);
        return $this->id . ':weekly:' . $weekbeginning . ':mec';
    }

    public function getThisWeeksEnglishActivitiesCount($date = null)
    {
        if (is_null($date)) {
            $date = today()->toDateString();
        }
        $weekbeginning = (new myDate($date))->thisMonday($date);
        return $this->thisWeeksActivities($weekbeginning)
            ->sum(function ($activity) {
                return $activity->subjects->where('name', 'English')->count();
            });
    }

    public function getThisWeeksMathsActivitiesCount($date = null)
    {
        if (is_null($date)) {
            $date = today()->toDateString();
        }
        $weekbeginning = (new myDate($date))->thisMonday($date);
        $activities = $this->thisWeeksActivities($weekbeginning);
        return $activities->sum(function ($activity) {
            return $activity->subjects->where('name', 'Maths')->count();
        });
    }

    public function weeklyTargetsAchieved($date = null)
    {
        if (is_null($date)) {
            $date = today()->toDateString();
        }
        $weekbeginning = (new MyDate($date))->thisMonday($date);
        $activity_target_achieved
            = $this->getWeeklyActivitiesCount($date) >= \Config::get('constants.options.activities_target');
        $english_target_achieved
            = $this->getThisWeeksEnglishActivitiesCount($date) >= \Config::get('constants.options.english_target');
        $maths_target_achieved
            = $this->getThisWeeksMathsActivitiesCount($date) >= \Config::get('constants.options.maths_target');
        return $activity_target_achieved && $english_target_achieved && $maths_target_achieved;
    }

    public function nextActivity($activity)
    {
        $activities = $this->activities;
    }

    public function previousActivity($activity) {}

    public function activitySequenceNumber($activity_id) {}

    public function paginationKey($activity_id)
    {
        return ($this->activities->where('id', $activity_id)->keys()->first()) + 2;
    }

    // public static function getForm()
    // {
    //     return [

    //         Textinput::make('title')
    //             ->required()
    //             ->helperText('A brief description')
    //             ->maxLength(255),

    //     ];
    // }

    public static function getForm(): array
    {
        return [
            Textarea::make('surname'),
            Textarea::make('first_name'),
            Forms\Components\Select::make('location_id')
                ->relationship('location', 'location'),
            Toggle::make('on_roll')->default('selected')
        ];
    }
}
