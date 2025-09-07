<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches;

use App\Enums\LanguageCode;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

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
                            ->unique('domains', 'domain'),
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
                    ->schema([
                        Select::make('locale')
                            ->label(__('Language'))
                            ->required()
                            ->options(LanguageCode::class),
                    ])
                    ->columns(2)
                    ->compact(),
                Toggle::make('active')
                    ->translateLabel()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
