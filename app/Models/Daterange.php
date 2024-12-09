<?php

namespace App;

use Redis;

class Daterange 
{
	public $key;

    public function __construct($key)
    {
    	$user_id = \Auth::user()->id;
    	$this->key = $user_id.':'.$key; 

    	// is there already a value for this in Redis?
    	if(! Redis::get($this->key) == null)
    	{
    		$this->value = Redis::get($this->key);
    	}
    	else
    	{    	
	    	// create a default
		   	$end_date = \Carbon\Carbon::today()->toDateString();
	    	$start_date = \Carbon\Carbon::today()->subMonths(3)->toDateString();
	    	// set value of object
	    	$this->value = $start_date.' - '.$end_date;
	    	// store in Redis
	    	Redis::set($this->key, $this->value);
	    }
    }


    public function setValue($value)
    {
    	// for use with the date picker
    	// set value of object
    	$this->value = $value;
    	// store in Redis
    	Redis::set($this->key, $this->value);
    }

    public function getValue()
    {
    	return $this->value;
    }

    public function startDate()
    {
		$start_date=substr($this->getValue(), 0, 10);
		return new \Carbon\Carbon($start_date);
    }

    public function endDate()
    {
		$end_date=substr($this->getValue(), 13, 23);
		return  new \Carbon\Carbon($end_date);
    }

    public function niceDate($date_string)
    {
    	return $date_string->format('jS F Y'); 
    }
}
