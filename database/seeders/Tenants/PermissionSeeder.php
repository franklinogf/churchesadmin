<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Enums\TenantPermissionName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

final class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect(TenantPermissionName::values())
            ->map(fn ($permission) => ['name' => $permission, 'guard_name' => 'web']);

        Permission::insert($permissions->toArray());
    }
}
