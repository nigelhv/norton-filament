<?php

namespace App;

class StudentAttendanceStats 
{
    public function __construct($year)
    {
    	$this->year = $year;
    }

    public function totalPossibleAttendances()
    {
    	$students = \App\Student::pluck('id')->toArray();
    	$blank_array = array();
		foreach($students as $student){$blank_array += [$student => 0]; }
		$attendances = \App\Attendance::where('date', '>=', $this->year.'-09-01')
			->where('date', '<', ($this->year + 1).'-09-01')
			->pluck('student_id')	
			->toArray();
		$full_attendances_table = array_replace($blank_array, array_count_values($attendances));
    	return $full_attendances_table;
    }

    public function totalActualAttendances()
    {
    	$students = \App\Student::pluck('id')->toArray();
    	$blank_array = array();
		foreach($students as $student){$blank_array += [$student => 0]; }
		$attendances = \App\Attendance::where('date', '>=', $this->year.'-09-01')
			->where('date', '<', ($this->year + 1).'-09-01')
			->where('value', 1)
			->pluck('student_id')	
			->toArray();
		$full_attendances_table = array_replace($blank_array, array_count_values($attendances));
    	return $full_attendances_table;
    }


}
