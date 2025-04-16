<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChurchResource\Pages;
use App\Filament\Resources\ChurchResource\RelationManagers;
use App\Models\Church;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;

class ChurchResource extends Resource
{
    protected static ?string $model = Church::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Repeater::make('domains')
                ->required()
                ->relationship()
                ->simple(
                    Forms\Components\TextInput::make('domain')
                    ->prefix('https://')
                    ->suffix('.'.str(config('app.url'))->after('://'))
                        ->required()
                        ->unique('domains', 'domain', ignorable: fn (Domain $record): Domain => $record),
                )
                ->extraItemActions([
                    function (string $operation): Forms\Components\Actions\Action|null {
                        if ($operation !== 'create') {
                            return null;
                        }
                        return Forms\Components\Actions\Action::make('Go to website')
                        ->url(fn (Church $record): string => tenant_route($record->domains()->first()->domain.'.'. str(config('app.url'))->after('://'),'home'))
                        ->openUrlInNewTab()
                        ->icon('heroicon-o-globe-alt');
                    },
                ])
                ->deletable(false)
                ->maxItems(1)
                ->minItems(1)
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Go to website')
                    ->url(fn (Church $record): string => tenant_route($record->domains()->first()->domain.'.'. str(config('app.url'))->after('://'),'home'))
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
}
