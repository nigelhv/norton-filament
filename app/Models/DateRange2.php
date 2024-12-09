<?php

namespace App;

class DateRange2 
{
	public $value;


    public function __construct($value=null)
    {
    	if($value == null){
    		if($this->getFromRedis() == null)
    		{
	    		$this->value = $this->setDefault();
    		}
    		else
    		{
    			$this->value = $this->getFromRedis();
    		}
    	}
    	else{
    		$this->storeInRedis();
	    	$this->value=$value;
	    }
    }

    public function setDefault()
    {
    	// create a default
	   	$end_date = \Carbon\Carbon::today()->toDateString();
    	$start_date = \Carbon\Carbon::today()->subMonths(3)->toDateString();
    	// set value of object
    	$this->value = $start_date.' - '.$end_date;
    	$this->storeInRedis();
    }

    private function storeInRedis()
    {
    	// $user_id = $user_id=\Auth::user()->id;
		$user_id = 1;
    	// \Redis::set($user_id.":activities_daterange", $this->value);
    }

    private function getFromRedis()
    {
    	$user_id = $user_id=\Auth::user()->id;
    	return \Redis::get($user_id.":activities_daterange");
    }

    public function startDate()
    {
		return substr($this->value, 0, 10);
    }

    public function endDate()
    {
		return substr($this->value, 13, 23);
    }

	public function niceDate($date_string)
	{

		if(! is_null($date_string))
		{
			if(is_string($date_string))
			{

				// expects a string
				$mydate=new \Carbon\Carbon($date_string);
                // dd($mydate);
			}
			else
			{
				$mydate=$date_string;
			}
            // dd($mydate);
			// expects carbon
			$niceDate=$mydate->format('jS F Y'); 

			return $niceDate;
		}
		return null;
	}

    public function present()
    {
    	return $this->niceDate($this->startDate()).' - '.$this->niceDate($this->endDate());
    }
}
