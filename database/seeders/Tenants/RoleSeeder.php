<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Enums\TenantPermission;
use App\Enums\TenantRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['name' => TenantRole::SUPER_ADMIN, 'guard_name' => 'tenant'])
            ->givePermissionTo(TenantPermission::values());

        Role::create(['name' => TenantRole::ADMIN, 'guard_name' => 'tenant'])
            ->givePermissionTo(
                collect(TenantPermission::values())
                    ->filter(fn (string $permission) => ! str($permission)->startsWith('users'))
                    ->toArray()
            );

        Role::create(['name' => TenantRole::SECRETARY, 'guard_name' => 'tenant'])
            ->givePermissionTo(collect(TenantPermission::values())
                ->filter(fn (string $permission) => ! str($permission)->endsWith('delete') && ! str($permission)->startsWith('users'))
                ->toArray()
            );

        Role::create(['name' => TenantRole::NO_ROLE, 'guard_name' => 'tenant']);

    }
}
