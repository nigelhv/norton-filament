<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Activity;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Total activities', Activity::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total students', Student::count())

                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make('Total subjects', Subject::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total teachers', User::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Worcester teachers', User::where('location_id', 1)->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Tewkesbury teachers', User::where('location_id', 2)->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('In orperation since', Activity::orderBy('date')->limit(1)->first()->date)

        ];
    }
}
