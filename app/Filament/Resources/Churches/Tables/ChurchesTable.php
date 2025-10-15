<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Tables;

use App\Enums\MediaCollectionName;
use App\Models\Church;
use App\Models\TenantUser;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Component;

final class ChurchesTable
{
    public static function configure(Table $table): Table
    {
        function userSchema(TenantUser $user): Section
        {
            $id = $user->id;

            return Section::make($user->name)
                ->compact()
                ->schema([
                    TextEntry::make('email')
                        ->default($user->email),
                    TextEntry::make('roles')
                        ->default($user->roles->pluck('name')->implode(', ')),

                    ActionGroup::make([
                        Action::make("impersonate_{$id}")
                            ->label(__('Log in as user'))
                            ->action(function (Church $record, Component $livewire) use ($id): void {
                                $token = tenancy()->impersonate($record, $id, '/dashboard', 'tenant');
                                $url = create_tenant_url($record, 'impersonate', ['token' => $token]);
                                $livewire->js("window.open('$url', '_blank');");

                            })
                            ->icon('heroicon-o-user-circle')
                            ->color('danger'),
                        Action::make("edit_{$id}")
                            ->label(__('Update Password'))
                            ->url(null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-pencil')
                            ->color('warning')
                            ->button(),
                    ])->buttonGroup(),
                ]);

        }

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
                    ->url(fn (Church $record): string => create_tenant_url($record, 'home'))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt'),
                Action::make('Users')
                    ->icon('heroicon-o-users')
                    ->color('secondary')
                    ->slideOver()
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalAutofocus(false)
                    ->schema(function (Church $record): array {

                        $users = $record->run(fn (): Collection => TenantUser::query()->with('roles')->get(['id', 'name', 'email']));
                        $schema = $users->isEmpty()
                        ? [TextEntry::make('no_users')->default('No users found.')]
                        : $users->map(fn (TenantUser $user): Section => userSchema($user))->toArray();

                        return $schema;
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
