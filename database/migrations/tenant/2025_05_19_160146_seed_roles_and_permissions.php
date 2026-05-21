<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Enums\TenantRole;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $guardName = 'tenant';
        // Seeding permissions first
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(TenantPermission::values())
            ->map(fn (string $permission): array => ['name' => $permission, 'guard_name' => $guardName]);

        Permission::query()->insert($permissions->all());

        // seeding roles with the permissions

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::create(['name' => TenantRole::SUPER_ADMIN, 'guard_name' => $guardName])
            ->givePermissionTo(TenantPermission::values());

        Role::create(['name' => TenantRole::ADMIN, 'guard_name' => $guardName])
            ->givePermissionTo(
                collect(TenantPermission::values())
                    ->reject(fn (string $permission): bool => str($permission)->startsWith('users'))
                    ->all()
            );

        Role::create(['name' => TenantRole::SECRETARY, 'guard_name' => $guardName])
            ->givePermissionTo(collect(TenantPermission::values())
                ->filter(fn (string $permission): bool => ! str($permission)->endsWith('delete') && ! str($permission)->startsWith('users'))
                ->all()
            );

        Role::create(['name' => TenantRole::NO_ROLE, 'guard_name' => $guardName]);
    }
};
