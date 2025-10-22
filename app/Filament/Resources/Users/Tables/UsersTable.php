<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\Pages\EditUser;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

final class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
                //
            ])

            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record): bool => $record->id !== Auth::id()),
                DeleteAction::make()
                    ->visible(fn ($record): bool => $record->id !== Auth::id()),
            ])
            ->recordUrl(fn ($record): string => $record->id !== Auth::id() ? EditUser::getUrl(['record' => $record]) : '')
            ->toolbarActions([
                //
            ]);
    }
}
