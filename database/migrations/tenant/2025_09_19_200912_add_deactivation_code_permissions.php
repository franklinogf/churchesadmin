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
            TenantPermission::DEACTIVATION_CODES_MANAGE->value,
            TenantPermission::DEACTIVATION_CODES_CREATE->value,
            TenantPermission::DEACTIVATION_CODES_UPDATE->value,
            TenantPermission::DEACTIVATION_CODES_DELETE->value,
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

        $secretaryRole = Role::where('name', TenantRole::SECRETARY->value)->first();
        if ($secretaryRole) {
            // Secretary gets all permissions except delete (following existing pattern)
            $secretaryPermissions = array_filter($newPermissions, function ($permission) {
                return ! str_ends_with($permission, '.delete');
            });
            $secretaryRole->givePermissionTo($secretaryPermissions);
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

        // Remove the DeactivationCode permissions
        $permissionsToRemove = [
            TenantPermission::DEACTIVATION_CODES_MANAGE->value,
            TenantPermission::DEACTIVATION_CODES_CREATE->value,
            TenantPermission::DEACTIVATION_CODES_UPDATE->value,
            TenantPermission::DEACTIVATION_CODES_DELETE->value,
        ];

        Permission::whereIn('name', $permissionsToRemove)
            ->where('guard_name', $guardName)
            ->delete();

        // Clear permission cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
