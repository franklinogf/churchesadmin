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

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $newPermissions = [
            TenantPermission::BOOKS_MANAGE->value,
            TenantPermission::BOOKS_CREATE->value,
            TenantPermission::BOOKS_UPDATE->value,
            TenantPermission::BOOKS_DELETE->value,
        ];

        foreach ($newPermissions as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        $superAdminRole = Role::query()->where('name', TenantRole::SUPER_ADMIN->value)->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($newPermissions);
        }

        $adminRole = Role::query()->where('name', TenantRole::ADMIN->value)->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($newPermissions);
        }

        $secretaryRole = Role::query()->where('name', TenantRole::SECRETARY->value)->first();
        if ($secretaryRole) {
            $secretaryRole->givePermissionTo(
                array_filter($newPermissions, fn (string $permission): bool => ! str_ends_with($permission, '.delete'))
            );
        }

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $guardName = 'tenant';

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::query()
            ->whereIn('name', [
                TenantPermission::BOOKS_MANAGE->value,
                TenantPermission::BOOKS_CREATE->value,
                TenantPermission::BOOKS_UPDATE->value,
                TenantPermission::BOOKS_DELETE->value,
            ])
            ->where('guard_name', $guardName)
            ->delete();

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
