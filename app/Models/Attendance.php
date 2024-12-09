<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
	use SoftDeletes;

    protected $guarded = [];
    
    public function student()
    {
    	return $this->belongsTo(Student::class);
    }

    public function attendance_code()
    {
    	return $this->belongsTo(AttendanceCode::class);
    }

    public function forget()
    {
    	\Redis::decr('attendance:'.$this->student_id.':'.$this->attendance_code_id);
    }

    public function remove()
    {
    	$this->delete();
    	$this->forget();
    }
}
