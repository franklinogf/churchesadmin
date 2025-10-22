<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Schemas;

use App\Enums\ChurchFeature;
use App\Enums\LanguageCode;
use App\Models\Church;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;
use Laravel\Pennant\Feature;

final class ChurchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->translateLabel()
                    ->required(),
                Repeater::make('domains')
                    ->translateLabel()
                    ->required()
                    ->relationship()
                    ->simple(
                        TextInput::make('domain')
                            ->prefix('https://')
                            ->suffix('.'.str(config('app.url'))->after('://'))
                            ->required()
                        // ->unique('domains', 'domain', ignoreRecord: true),
                    )
                    ->extraItemActions([
                        function (string $operation): ?Action {
                            if ($operation === 'create') {
                                return null;
                            }

                            return Action::make('Go to website')
                                ->translateLabel()
                                ->url(fn (Church $record): string => tenant_route($record->domains()->first()->domain.'.'.str(config('app.url'))->after('://'), 'home'))
                                ->openUrlInNewTab()
                                ->icon('heroicon-o-globe-alt');
                        },
                    ])
                    ->deletable(false)
                    ->maxItems(1)
                    ->minItems(1),
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
                    ->translateLabel()
                    ->divided()
                    ->schema([
                        CheckboxList::make('features')
                            ->dehydrated(false)
                            ->columns(2)
                            ->label(__('Enabled Features'))
                            ->options(ChurchFeature::class)
                            ->afterStateHydrated(function (CheckboxList $component, ?Church $record, string $operation): void {
                                if ($operation !== 'edit') {
                                    return;
                                }
                                $activeFeatures = collect(ChurchFeature::values())
                                    ->filter(fn ($key) => Feature::for($record)->active($key))
                                    ->toArray();

                                $component->state($activeFeatures);
                            }),
                    ])
                    ->columnSpanFull()
                    ->compact(),
            ]);
    }
}
