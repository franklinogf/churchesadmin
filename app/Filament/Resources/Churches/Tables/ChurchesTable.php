<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Tables;

use App\Enums\MediaCollectionName;
use App\Models\Church;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

final class ChurchesTable
{
    public static function configure(Table $table): Table
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
}
