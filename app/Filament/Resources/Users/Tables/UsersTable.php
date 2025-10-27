<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\Church;
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
                    ->visible(fn (Church $record): bool => $record->id !== Auth::id()),
                DeleteAction::make()
                    ->visible(fn (Church $record): bool => $record->id !== Auth::id()),
            ])
            ->recordUrl(fn (Church $record): string => $record->id !== Auth::id() ? EditUser::getUrl(['record' => $record]) : '')
            ->toolbarActions([
                //
            ]);
    }
}
