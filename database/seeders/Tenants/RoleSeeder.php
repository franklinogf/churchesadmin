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

        Role::create(['name' => TenantRole::SUPER_ADMIN])
            ->givePermissionTo(TenantPermission::cases());

        Role::create(['name' => TenantRole::ADMIN])
            ->givePermissionTo(
                collect(TenantPermission::cases())
                    ->filter(fn (TenantPermission $permission) => ! str_contains($permission->value, '_USERS'))
                    ->toArray()
            );

        Role::create(['name' => TenantRole::SECRETARY])
            ->givePermissionTo(collect(TenantPermission::cases())
                ->filter(fn (TenantPermission $permission) => ! str_contains($permission->value, 'DELETE_'))
                ->toArray()
            );

    }
}
