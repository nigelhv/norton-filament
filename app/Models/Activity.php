<?php

namespace App\Models;

use DateTimeImmutable;
use Spatie\Image\Manipulations;

use Spatie\MediaLibrary\HasMedia;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\CheckboxList;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Activity extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description'];


    protected static function booted()
    {
        static::addGlobalScope(new Scopes\LocationScope);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot('notes');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('media')
            ->onlyKeepLatest(3);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public static function last()
    {
        return static::all()->last();
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->performOnCollections('media')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
        $this->addMediaConversion('thumb')
            ->performOnCollections('media')
            ->nonQueued()
            ->width(100)
            ->height(100)
            ->sharpen(10);
    }

    public function myDateFormat()
    {

        return  date_format(new DateTimeImmutable($this->date), 'd M Y');
    }

    public function myLongDateFormat()
    {

        return  date_format(new DateTimeImmutable($this->date), 'jS F Y');
    }

    public function anyEnglish()
    {
        $subjects = $this->subjects;
        foreach ($subjects as $subject) {
            if ($subject->name == "English") {
                return true;
            }
        }
        return false;
    }

    public function anyMaths()
    {
        $subjects = $this->subjects;
        foreach ($subjects as $subject) {
            if ($subject->name == "Maths") {
                return true;
            }
        }
        return false;
    }

    public function hasSubject($subject)
    {
        foreach ($this->subjects as $subject) {
            if ($subject->name == $subject) {
                return true;
            }
            return false;
        }
    }

    public static function getForm()
    {
        return [

            Textinput::make('title')
                ->required()
                ->helperText('A brief description')
                ->maxLength(255),
            Select::make('users')
                ->preload()
                ->multiple()
                ->relationship('users', 'surname')
                ->required()
                ->helperText('Needs to pick from staff list'),
            RichEditor::make('description')
                ->required()
                ->columnSpanFull(),
            DatePicker::make('date')
                ->required()
                ->native(false),
            Select::make('location_id')
                ->preload()
                ->relationship('location', 'location'),
            Select::make('students')
                ->preload()
                ->multiple()
                ->relationship('students', 'surname')
                ->required()
                ->createOptionForm(Student::getForm())
                ->editOptionForm(Student::getForm())
                ->helperText('Pick from student list'),
            CheckboxList::make('subjects')
                ->relationship('subjects', 'name')
                ->required()
                ->columns(3)
                ->searchable()
                ->helperText('Needs to pick from subject list'),
        ];
    }
}
