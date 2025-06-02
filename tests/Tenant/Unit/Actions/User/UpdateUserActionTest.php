<?php

declare(strict_types=1);

use App\Actions\User\UpdateUserAction;
use App\Models\TenantUser;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

it('can update user basic data', function (): void {
    $user = TenantUser::factory()->create([
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
    ]);

    $updateData = [
        'name' => 'Jane Smith',
        'email' => 'jane.smith@example.com',
    ];

    $action = new UpdateUserAction();
    $action->handle($user, $updateData);

    $user->refresh();

    expect($user->name)->toBe('Jane Smith')
        ->and($user->email)->toBe('jane.smith@example.com');
});

it('can update user roles', function (): void {
    $user = TenantUser::factory()->create();

    Role::create(['name' => 'role1']);
    Role::create(['name' => 'role2']);

    $user->assignRole(['role1']);

    $newRoles = ['role2'];

    $action = new UpdateUserAction();
    $action->handle($user, roles: $newRoles);

    $user->refresh();

    expect($user->hasRole('role2'))->toBeTrue()
        ->and($user->hasRole('role1'))->toBeFalse()
        ->and($user->roles()->count())->toBe(1);
});

it('can update user permissions', function (): void {
    $user = TenantUser::factory()->create();

    // Create permissions
    Permission::create(['name' => 'permission1']);
    Permission::create(['name' => 'permission2']);
    Permission::create(['name' => 'permission3']);

    // Give user initial permissions
    $user->givePermissionTo(['permission1', 'permission2']);

    $newPermissions = ['permission3'];

    $action = new UpdateUserAction();
    $action->handle($user, permissions: $newPermissions);

    $user->refresh();

    expect($user->hasPermissionTo('permission2'))->toBeFalse()
        ->and($user->hasPermissionTo('permission3'))->toBeTrue()
        ->and($user->hasPermissionTo('permission1'))->toBeFalse()
        ->and($user->permissions()->count())->toBe(1);
});

it('can clear all roles and permissions', function (): void {
    $user = TenantUser::factory()->create();

    Role::create(['name' => 'role1']);
    Permission::create(['name' => 'permission1']);

    $user->assignRole('role1');
    $user->givePermissionTo('permission1');

    $roles = []; // Empty array to clear all roles
    $permissions = []; // Empty array to clear all permissions

    $action = new UpdateUserAction();
    $action->handle($user, roles: $roles, permissions: $permissions);

    $user->refresh();

    expect($user->roles()->count())->toBe(0)
        ->and($user->permissions()->count())->toBe(0);
});
