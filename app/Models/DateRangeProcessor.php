<?php

namespace App;



class DateRangeProcessor 
{
	public $daterange;

    public function __construct($daterange)
    {
    	$this->daterange = $daterange;
    	$this->start_date = $this->startDate();
    	$this->end_date = $this->endDate();
    	$this->nice_start_date = $this->niceStartDate();
    	$this->nice_end_date = $this->niceEndDate();
    }

    protected function startDate()
    {
    	return substr($this->daterange, 0, 10);
    }

    protected function endDate()
    {
    	return substr($this->daterange, 13, 22);
    }

	public function niceDate($date_string)
	{
		$mydate=new \Carbon\Carbon($date_string);
		$niceDate=$mydate->format('jS F Y'); 
		return $niceDate;

	}

	public function niceEndDate()
	{
		return $this->niceDate($this->end_date);
	}
	public function niceStartDate()
	{
		return $this->niceDate($this->start_date);
	}

}
