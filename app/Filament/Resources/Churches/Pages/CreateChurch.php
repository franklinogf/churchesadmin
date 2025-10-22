<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Pages;

use App\Enums\TenantRole;
use App\Filament\Resources\Churches\ChurchResource;
use App\Models\Church;
use App\Models\CurrentYear;
use App\Models\TenantUser;
use Filament\Resources\Pages\CreateRecord;

/**
 * @property-read Church $record
 */
final class CreateChurch extends CreateRecord
{
    protected static string $resource = ChurchResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();

        $church = $this->record;

        tenancy()->run($church, function () use ($data): void {
            $user = TenantUser::create([
                'name' => 'Super Admin',
                'email' => $data['email'],
                'email_verified_at' => null,
                'password' => $data['password'],
                'current_year_id' => CurrentYear::first()?->id ?? 1,
            ]);

            $user->assignRole(TenantRole::SUPER_ADMIN->value);
        });

        $features = $this->data['features'] ?? [];

        foreach ($features as $feature) {
            $church->features()->activate($feature);
        }
    }
}
