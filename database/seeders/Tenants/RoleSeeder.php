<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Enums\TenantPermissionName;
use App\Enums\TenantRoleName;
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

        Role::create(['name' => TenantRoleName::ADMIN])
            ->givePermissionTo(TenantPermissionName::cases());

        Role::create(['name' => TenantRoleName::SECRETARY])
            ->givePermissionTo([
                TenantPermissionName::MANAGE_SKILLS,
                TenantPermissionName::CREATE_SKILLS,
                TenantPermissionName::UPDATE_SKILLS,
                TenantPermissionName::MANAGE_CATEGORIES,
                TenantPermissionName::CREATE_CATEGORIES,
                TenantPermissionName::UPDATE_CATEGORIES,
                TenantPermissionName::MANAGE_MEMBERS,
                TenantPermissionName::CREATE_MEMBERS,
                TenantPermissionName::UPDATE_MEMBERS,
                TenantPermissionName::MANAGE_MISSIONARIES,
                TenantPermissionName::CREATE_MISSIONARIES,
                TenantPermissionName::UPDATE_MISSIONARIES,

            ]);

    }
}
