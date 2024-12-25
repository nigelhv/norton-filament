<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\Page;

class Rpcp extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home'; // Set an icon for the navigation
    protected static ?string $navigationLabel = 'Your Page'; // Set the label for the navigation
    protected static ?int $navigationSort = 2; // Optional: Control the order in the navigation menu

    protected static string $view = 'filamen.resources.userresources.pages.rpcp';

    // Optional: Define the navigation group
    protected static ?string $navigationGroup = 'Custom Pages';
    protected static string $resource = UserResource::class;

    // protected static string $view = 'filament.resources.user-resource.pages.rpcp';
}
