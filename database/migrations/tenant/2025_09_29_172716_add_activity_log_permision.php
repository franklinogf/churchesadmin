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

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create the new DeactivationCode permissions
        $newPermissions = [
            TenantPermission::ACTIVITY_LOGS_MANAGE->value,
        ];

        foreach ($newPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        // Assign permissions to roles
        $superAdminRole = Role::where('name', TenantRole::SUPER_ADMIN->value)->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($newPermissions);
        }

        $adminRole = Role::where('name', TenantRole::ADMIN->value)->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($newPermissions);
        }

        // Clear permission cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $guardName = 'tenant';

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsToRemove = [
            TenantPermission::ACTIVITY_LOGS_MANAGE->value,
        ];

        // Remove permissions from roles
        $roles = Role::whereIn('name', [TenantRole::SUPER_ADMIN->value, TenantRole::ADMIN->value])->get();
        foreach ($roles as $role) {
            $role->revokePermissionTo($permissionsToRemove);
        }

        // Delete the permissions
        Permission::whereIn('name', $permissionsToRemove)
            ->where('guard_name', $guardName)
            ->delete();

        // Clear permission cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
