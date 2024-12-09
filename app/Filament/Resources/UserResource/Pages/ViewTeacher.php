<?php

namespace App\Filament\Resources\UserResource\Pages;



use App\Teacher;
use App\Models\User;
use Filament\Actions\EditAction;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;





class ViewTeacher extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->slideOver()

        ];
    }
}
