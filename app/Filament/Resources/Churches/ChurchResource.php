<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches;

use App\Enums\LanguageCode;
use App\Enums\MediaCollectionName;
use App\Filament\Resources\Churches\Pages\CreateChurch;
use App\Filament\Resources\Churches\Pages\EditChurch;
use App\Filament\Resources\Churches\Pages\ListChurches;
use App\Models\Church;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Password;

final class ChurchResource extends Resource
{
    protected static ?string $model = Church::class;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-church';

    public static function form(Schema $schema): Schema
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->state(function (Church $record): ?string {
                        $logoPath = $record->getFirstMedia(MediaCollectionName::LOGO->value)?->getPathRelativeToRoot();

                        if ($logoPath === null) {
                            return null;
                        }

                        return mb_rtrim(config('app.url'), '/')."/public-{$record->id}/{$logoPath}";
                    })
                    ->label(__('Logo'))
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->imageSize(40),
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('locale')
                    ->label(__('Language'))
                    ->sortable()
                    ->badge(),
                ToggleColumn::make('active')
                    ->translateLabel()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Go to website')
                    ->translateLabel()
                    ->url(fn (Church $record): string => tenant_route($record->domains()->first()->domain.'.'.str(config('app.url'))->after('://'), 'home'))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt'),
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChurches::route('/'),
            'create' => CreateChurch::route('/create'),
            'edit' => EditChurch::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) self::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of churches';
    }
}
