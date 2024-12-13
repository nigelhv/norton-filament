<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use Filament\Actions;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ActivityResource;

class ViewActivity extends ViewRecord
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->slideOver()

        ];
    }
}
