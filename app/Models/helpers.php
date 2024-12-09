<?php

function niceDate($date)
{
	if(! is_null($date))
	{
		if(is_string($date))
		{
			// expects a string
		$mydate=new \Carbon\Carbon($date);
		}
		else
		{
			$mydate=$date;
		}
		// expects a string
		$niceDate=$mydate->format('jS F Y'); 
		return $niceDate;
	}
	return null;
}