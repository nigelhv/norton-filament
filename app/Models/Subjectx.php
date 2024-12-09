<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
	use SoftDeletes;

	public function activities()
	{
	    return $this->belongsToMany(Activity::class);
	}
}
