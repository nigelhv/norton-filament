<?php

namespace App\Models;

class MyDate
{
    public function __construct($date = null)
    {
        $this->date = $date;
        if ($date !== null) {
            $this->date = \Carbon\Carbon::parse($date);
        } else {
            $this->date = \Carbon\Carbon::today();
        }
    }

    public function thisMonday()
    {
        $monday = $this
            ->date
            ->copy()
            ->subDays($this->date->dayOfWeek)
            ->addDays(1)
            ->toDateString();
        return $monday;
    }

    public function thisTuesday()
    {
        $tuesday = $this
            ->date
            ->copy()
            ->subDays($this->date->dayOfWeek)
            ->addDays(2)
            ->toDateString();
        return $tuesday;
    }

    public function thisWednesday()
    {
        $wednesday = $this
            ->date
            ->copy()
            ->subDays($this->date->dayOfWeek)
            ->addDays(3)
            ->toDateString();
        return $wednesday;
    }

    public function thisThursday()
    {
        $thursday = $this
            ->date
            ->copy()
            ->subDays($this->date->dayOfWeek)
            ->addDays(4)
            ->toDateString();
        return $thursday;
    }

    public function thisFriday()
    {
        $friday = $this
            ->date
            ->copy()
            ->subDays($this->date->dayOfWeek)
            ->addDays(5)
            ->toDateString();
        return $friday;
    }

    public function niceDate()
    {

        if (!is_null($this->date)) {
            if (is_string($this->date)) {
                // expects a string
                $mydate = new \Carbon\Carbon($this->date);
            } else {
                $mydate = $this->date;
            }
            // expects a string
            $niceDate = $mydate->format('jS F Y');
            return $niceDate;
        }
        return null;
    }

    public function future()
    {
        if ($this->date > date('Y-m-d')) {
            return true;
        }
        return false;
    }

    public function setYearStartDate()
    {
        // work out correct 1st September
        $dt = new \Carbon\Carbon($this->date);
        $year = (new \Carbon\Carbon($this->date))->year;
        // if current month is Jan - Aug, school year started last calendar year
        if ($dt->month < 9) {
            $year = $year - 1;
        }
        $start_date = $year . '-09-01';
        //put into session
        \Session::put('yearStartDate', $start_date);
        return $start_date;
    }


    public function yearStartDate()
    {
        // if the date is in the session, return that
        if (\Session::has('yearStartDate')) {
            return \Session::get('yearStartDate');
        }
        // otherwise, generate the date and put it in the session
        return $this->setYearStartDate();
    }

    public function dateSequence($start_date, $end_date)
    {
        $start = new \Carbon\Carbon($start_date);
        $end = new \Carbon\Carbon($end_date);
        $sequence = [];
        $current_date = $start->copy();
        while ($current_date <= $end) {
            array_push($sequence, $current_date->toDateString());
            $current_date->addDays(1);
        }
        return $sequence;
    }
}
