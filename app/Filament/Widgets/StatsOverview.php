<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Activity;
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
            Stat::make('In orperation since', Activity::orderBy('created_at')->limit(1)->first()->created_at->since())

        ];
    }
}
