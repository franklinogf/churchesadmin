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

        // Create the new permissions
        $newPermissions = [
            TenantPermission::SETTINGS_MANAGE->value,
            TenantPermission::SETTINGS_CLOSE_YEAR->value,
            TenantPermission::SETTINGS_CHANGE_LANGUAGE->value,
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
            $adminRole->givePermissionTo([
                TenantPermission::SETTINGS_MANAGE->value,
                TenantPermission::SETTINGS_CHANGE_LANGUAGE->value,
            ]);
        }

        // Clear permission cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
