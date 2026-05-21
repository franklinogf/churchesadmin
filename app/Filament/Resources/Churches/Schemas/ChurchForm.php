<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Schemas;

use App\Data\TenantFeatures;
use App\Enums\LanguageCode;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

final class ChurchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->translateLabel()
                    ->required(),
                TextInput::make('domain')
                    ->prefix('https://')
                    ->suffix('.'.str(config('app.url'))->after('://'))
                    ->required()
                    ->unique(ignoreRecord: true),
                Section::make('Settings')
                    ->translateLabel()
                    ->divided()
                    ->schema([
                        Select::make('locale')
                            ->label(__('Language'))
                            ->required()
                            ->options(LanguageCode::class),
                        Toggle::make('active')
                            ->default(true)
                            ->translateLabel()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->compact(),

                Section::make('Super admin user')
                    ->translateLabel()
                    ->hiddenOn('edit')
                    ->schema([
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->required()
                            ->columnSpanFull()
                            ->dehydrated(false),
                        TextInput::make('password')
                            ->required()
                            ->label(__('Password'))
                            ->password()
                            ->rule(Password::defaults())
                            ->revealable()
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->compact(),
                Section::make('Features')
                    ->statePath('features')
                    ->schema(collect((new TenantFeatures)->toArray())
                        ->map(fn (bool $value, string $key): Toggle => Toggle::make($key)->default($value))
                        ->all()
                    )->columns(2),
            ]);
    }
}
