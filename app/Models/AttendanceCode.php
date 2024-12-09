<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceCode extends Model
{
    public function attendances()
    {
    	return @$this->hasMany(Attendance::class);
    }
}
