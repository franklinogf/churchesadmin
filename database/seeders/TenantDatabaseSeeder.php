<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\LanguageCode;
use App\Enums\WalletName;
use App\Models\Church;
use Illuminate\Database\Seeder;

final class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the tenants's database.
     */
    public function run(): void
    {
        if (app()->environment('testing')) {
            return;
        }
        $this->call([
            Tenants\PermissionSeeder::class,
            Tenants\RoleSeeder::class,
            Tenants\CategorySeeder::class,
            Tenants\UserSeeder::class,
        ]);
        $currentChurch = Church::current();

        $currentChurch?->createWallet([
            'name' => $currentChurch?->locale === LanguageCode::EN ? 'Primary Wallet' : 'Billetera Principal',
            'description' => $currentChurch?->locale === LanguageCode::EN ? 'This is the primary wallet' : 'Esta es la billetera principal',
            'slug' => WalletName::PRIMARY->value,
        ]);

    }
}
