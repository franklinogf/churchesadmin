<?php

declare(strict_types=1);

use App\Actions\User\CreateUserAction;
use App\Models\TenantUser;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

it('can create a user with basic data', function (): void {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
    ];

    $action = new CreateUserAction();
    $action->handle($userData);

    $user = TenantUser::latest()->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john.doe@example.com');
});

it('can create a user with roles', function (): void {

    Role::create(['name' => 'role1']);
    Role::create(['name' => 'role2']);

    $userData = [
        'name' => 'Jane Smith',
        'email' => 'jane.smith@example.com',
        'password' => 'password123',
    ];

    $roles = ['role1', 'role2'];

    $action = new CreateUserAction();
    $action->handle($userData, $roles);

    $user = TenantUser::latest()->first();

    expect($user)->not->toBeNull()
        ->and($user->hasRole($roles))->toBeTrue()
        ->and($user->roles()->count())->toBe(2);
});

it('can create a user with permissions', function (): void {
    // Create permissions first
    Permission::create(['name' => 'permission1']);
    Permission::create(['name' => 'permission2']);

    $userData = [
        'name' => 'Bob Wilson',
        'email' => 'bob.wilson@example.com',
        'password' => 'password123',
    ];

    $permissions = ['permission1', 'permission2'];

    $action = new CreateUserAction();
    $action->handle($userData, null, $permissions);

    $user = TenantUser::latest()->first();

    expect($user)->not->toBeNull();
    expect($user->hasPermissionTo('permission1'))->toBeTrue()
        ->and($user->hasPermissionTo('permission2'))->toBeTrue()
        ->and($user->permissions()->count())->toBe(2);
});

it('can create a user with both roles and permissions', function (): void {
    // Create role and permissions
    Role::create(['name' => 'manager']);
    Permission::create(['name' => 'permission1']);
    Permission::create(['name' => 'permission2']);

    $userData = [
        'name' => 'Alice Johnson',
        'email' => 'alice.johnson@example.com',
        'password' => 'password123',
    ];

    $roles = ['manager'];
    $permissions = ['permission1', 'permission2'];

    $action = new CreateUserAction();
    $action->handle($userData, $roles, $permissions);

    $user = TenantUser::where('email', 'alice.johnson@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->hasRole('manager'))->toBeTrue()
        ->and($user->hasPermissionTo('permission1'))->toBeTrue()
        ->and($user->hasPermissionTo('permission2'))->toBeTrue()
        ->and($user->roles()->count())->toBe(1)
        ->and($user->permissions()->count())->toBe(2);
});
