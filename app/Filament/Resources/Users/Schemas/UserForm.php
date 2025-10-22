<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Password')
                    ->autocomplete('new-password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (string $operation, ?string $state): bool => $operation === 'create' || filled($state))
                    ->confirmed()
                    ->rule(Password::defaults())
                    ->revealable(),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->password()
                    ->rule(Password::defaults())
                    ->revealable()
                    ->dehydrated(false),

            ]);
    }
}
