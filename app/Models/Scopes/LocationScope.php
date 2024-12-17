<?php

namespace App\Models\Scopes;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LocationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (session()->has('location_id')) {
            $builder->where('location_id', session()->get('location_id'));
        }
    }
}
