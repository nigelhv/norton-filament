<?php

namespace App;

class Date 
{
    public function thisWeekStartDate()
    {
    	$today=\Carbon\Carbon::today();
    	return $today->startOfWeek();
    }

    public function thisWeekEndDate()
    {
    	$today=\Carbon\Carbon::today();
    	return $today->endOfWeek()->subDays(2);
    }

}
