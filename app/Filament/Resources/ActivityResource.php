<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TagsColumn;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\Pages\ViewActivity;
use App\Filament\Resources\ActivityResource\RelationManagers\StudentsRelationManager;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getNavigationBadge(): ?string
    {
        return Activity::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Activity::getform());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                TagsColumn::make('students.surname')
                    ->label('Student')
                    ->listWithLineBreaks(),
                Tables\Columns\TextColumn::make('title')
                    ->wrap()->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TagsColumn::make('users.surname')
                    ->label('Teacher'),
                TagsColumn::make('subjects.name')
                    ->label('Subject'),

            ])->persistSortInSession()
            ->defaultSort('date', 'desc')
            ->persistSearchInSession()
            ->persistColumnSearchesInSession()
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->filters([
                SelectFilter::make('subjects')
                    ->relationship('subjects', 'name'),
                SelectFilter::make('students')
                    ->relationship('students', 'surname')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Activity Information')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('description'),
                    ])->columns(1),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'view' => ViewActivity::route('/{record}'),

        ];
    }
}
