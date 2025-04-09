<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\User;
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
            Tenants\RoleSeeder::class,
            Tenants\CategorySeeder::class,
        ]);

        User::factory()->superAdmin()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        User::factory()->secretary()->create([
            'name' => 'Secretary',
            'email' => 'secretary@example.com',
        ]);

        Member::factory(5)->has(Address::factory())->create();

        Missionary::factory(5)->has(Address::factory())->create();

    }
}
