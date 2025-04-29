<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\LanguageCode;
use App\Filament\Resources\ChurchResource\Pages;
use App\Models\Church;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class ChurchResource extends Resource
{
    protected static ?string $model = Church::class;

    protected static ?string $navigationIcon = 'lucide-church';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required(),
                Forms\Components\Repeater::make('domains')
                    ->translateLabel()
                    ->required()
                    ->relationship()
                    ->simple(
                        Forms\Components\TextInput::make('domain')
                            ->prefix('https://')
                            ->suffix('.'.str(config('app.url'))->after('://'))
                            ->required()
                            ->unique('domains', 'domain', ignoreRecord: true),
                    )
                    ->extraItemActions([
                        function (string $operation): ?Forms\Components\Actions\Action {
                            if ($operation === 'create') {
                                return null;
                            }

                            return Forms\Components\Actions\Action::make('Go to website')
                                ->translateLabel()
                                ->url(fn (Church $record): string => tenant_route($record->domains()->first()->domain.'.'.str(config('app.url'))->after('://'), 'home'))
                                ->openUrlInNewTab()
                                ->icon('heroicon-o-globe-alt');
                        },
                    ])
                    ->deletable(false)
                    ->maxItems(1)
                    ->minItems(1),
                Forms\Components\Section::make('Settings')
                    ->translateLabel()
                    ->schema([
                        Forms\Components\Select::make('locale')
                            ->label(__('Language'))
                            ->required()
                            ->options(LanguageCode::class),
                    ])
                    ->columns(2)
                    ->compact(),
                Forms\Components\Toggle::make('active')
                    ->translateLabel()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('locale')
                    ->label(__('Language'))
                    ->sortable()
                    ->badge(),
                Tables\Columns\ToggleColumn::make('active')
                    ->translateLabel()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Go to website')
                    ->translateLabel()
                    ->url(fn (Church $record): string => tenant_route($record->domains()->first()->domain.'.'.str(config('app.url'))->after('://'), 'home'))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListChurches::route('/'),
            'create' => Pages\CreateChurch::route('/create'),
            'edit' => Pages\EditChurch::route('/{record}/edit'),
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
