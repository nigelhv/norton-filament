<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Tables\Columns\UserTotals;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\Pages\ViewTeacher;
use App\Filament\Resources\StudentResource\RelationManagers\ActivityRelationManager;

class UserResource extends Resource
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
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function getNavigationBadge(): ?string
    {
        return User::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('surname')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('location_id')
                    ->required()
                    ->dehydrated(false)
                    ->maxLength(100),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('surname')
                    ->searchable()->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                UserTotals::make('count')->label('how many')->sortable(),
                Tables\Columns\TextColumn::make('location.location')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('surname')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function  infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('first_name'),
                        TextEntry::make('surname'),
                        TextEntry::make('email'),
                        TextEntry::make('location.location'),
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivityRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => ViewTeacher::route('/{record}'),
            'settings' => Pages\Settings::route('/settings'),
        ];
    }
}
