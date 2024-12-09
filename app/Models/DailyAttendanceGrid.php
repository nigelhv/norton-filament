<?php

namespace App;

class DailyAttendanceGrid 
{
    public function __construct($date)
    {
    	$this->date = $date;
    }

    // public function blankGrid()
    // {
    // 	$students = \App\Student::pluck('id')->toArray();
    // 	foreach($students as $student)
    // 	{
    // 		foreach($days as $day)
    // 		{
    // 			$recordedAttendanceGrid[$student][$day]=false;
    // 		}
    // 	}
    // 	return $recordedAttendanceGrid;
    // }

    public function getAttendanceList()
    {
    	// $students = \App\Student::where('on_roll', 1)->pluck('id')->toArray();
    	return \App\Attendance::where('date', $this->date)->pluck('attendance_code_id', 'student_id')->toArray();
    }
}
