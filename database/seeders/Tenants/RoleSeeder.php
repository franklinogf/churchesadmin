<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Enums\TenantPermissionName;
use App\Enums\TenantRoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect(TenantPermissionName::values())
            ->map(fn ($permission) => ['name' => $permission, 'guard_name' => 'web']);
        Permission::insert($permissions->toArray());

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['name' => TenantRoleName::SUPER_ADMIN]);

        Role::create(['name' => TenantRoleName::ADMIN])
            ->givePermissionTo([
                TenantPermissionName::UPDATE_REGULAR_TAG,
                TenantPermissionName::DELETE_REGULAR_TAG,
                TenantPermissionName::CREATE_USERS,
                TenantPermissionName::UPDATE_USERS,
                TenantPermissionName::DELETE_USERS,
                TenantPermissionName::CREATE_SKILLS,
                TenantPermissionName::UPDATE_SKILLS,
                TenantPermissionName::DELETE_SKILLS,
                TenantPermissionName::CREATE_CATEGORIES,
                TenantPermissionName::UPDATE_CATEGORIES,
                TenantPermissionName::DELETE_CATEGORIES,
                TenantPermissionName::CREATE_MEMBERS,
                TenantPermissionName::UPDATE_MEMBERS,
                TenantPermissionName::DELETE_MEMBERS,
                TenantPermissionName::CREATE_MISSIONARIES,
                TenantPermissionName::UPDATE_MISSIONARIES,
                TenantPermissionName::DELETE_MISSIONARIES,
            ]);

        Role::create(['name' => TenantRoleName::SECRETARY])
            ->givePermissionTo([
                TenantPermissionName::CREATE_USERS,
                TenantPermissionName::UPDATE_USERS,
                TenantPermissionName::CREATE_SKILLS,
                TenantPermissionName::UPDATE_SKILLS,
                TenantPermissionName::CREATE_CATEGORIES,
                TenantPermissionName::UPDATE_CATEGORIES,
                TenantPermissionName::CREATE_MEMBERS,
                TenantPermissionName::UPDATE_MEMBERS,
                TenantPermissionName::CREATE_MISSIONARIES,
                TenantPermissionName::UPDATE_MISSIONARIES,

            ]);

    }
}
