<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;



class ListUsers extends ListRecords
{

    public $location_id;
    public $password;
    public $systemadmin;
    public $superuser;
    public $staff;
    public $SLT;
    public $admin;

    protected $rules = [
        'systemadmin' => 'required',
        'staff' => 'required',
        'SLT' => 'required',
        'admin' => 'required',
        'superuser' => 'required'
    ];
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
