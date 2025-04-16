<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\Tag;
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
            Tenants\PermissionSeeder::class,
            Tenants\RoleSeeder::class,
            Tenants\CategorySeeder::class,
        ]);

        User::factory()->superAdmin()->create();
        User::factory()->admin()->create();
        User::factory()->secretary()->create();
        User::factory()->noRole()->create();

        Member::factory(10)->has(Address::factory())->create();

        Missionary::factory(10)->has(Address::factory())->create();

        Tag::factory(10)
            ->category()
            ->create();

        Tag::factory(10)
            ->skill()
            ->create();

    }
}
