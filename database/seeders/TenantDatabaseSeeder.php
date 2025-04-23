<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
        $this->call([
            Tenants\PermissionSeeder::class,
            Tenants\RoleSeeder::class,
            Tenants\CategorySeeder::class,
            Tenants\UserSeeder::class,
        ]);

        Church::current()?->createWallet([
            'name' => [
                'en' => 'Primary Wallet',
                'es' => 'Billetera Principal',
            ],
            'description' => [
                'en' => 'This is the primary wallet',
                'es' => 'Esta es la billetera principal',
            ],
            'slug' => WalletName::PRIMARY->value,
        ]);

    }
}
