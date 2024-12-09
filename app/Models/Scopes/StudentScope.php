<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class StudentScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
          //     commented out to make factories work!
          if (!Auth::user() == null ){
                    $builder->where('on_roll',  1)->where('location_id', Auth::user()->location_id);
          }
    }

}
