<?php

namespace App\Filament\Widgets;

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
            Stat::make('Students', Student::count())

                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make('Subjects', Subject::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
