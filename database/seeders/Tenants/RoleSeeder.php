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

        Role::create(['name' => TenantRole::ADMIN])
            ->givePermissionTo(TenantPermission::cases());

        Role::create(['name' => TenantRole::SECRETARY])
            ->givePermissionTo([
                TenantPermission::MANAGE_SKILLS,
                TenantPermission::CREATE_SKILLS,
                TenantPermission::UPDATE_SKILLS,
                TenantPermission::MANAGE_CATEGORIES,
                TenantPermission::CREATE_CATEGORIES,
                TenantPermission::UPDATE_CATEGORIES,
                TenantPermission::MANAGE_MEMBERS,
                TenantPermission::CREATE_MEMBERS,
                TenantPermission::UPDATE_MEMBERS,
                TenantPermission::MANAGE_MISSIONARIES,
                TenantPermission::CREATE_MISSIONARIES,
                TenantPermission::UPDATE_MISSIONARIES,

            ]);

    }
}
