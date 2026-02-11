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

        // Create the new CalendarEvent permissions
        $newPermissions = [
            TenantPermission::CALENDAR_EVENTS_MANAGE->value,
            TenantPermission::CALENDAR_EVENTS_CREATE->value,
            TenantPermission::CALENDAR_EVENTS_UPDATE->value,
            TenantPermission::CALENDAR_EVENTS_DELETE->value,
            TenantPermission::CALENDAR_EVENTS_EXPORT->value,
            TenantPermission::CALENDAR_EVENTS_EMAIL->value,
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
};
