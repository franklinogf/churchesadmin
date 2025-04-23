<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

    }
}
