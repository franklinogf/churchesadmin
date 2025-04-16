<?php

declare(strict_types=1);

use App\Enums\TenantPermission;

it('has needed enums', function (): void {

    expect(TenantPermission::names())->toBe([
        'UPDATE_REGULAR_TAG',
        'DELETE_REGULAR_TAG',
        'CREATE_REGULAR_TAG',
        'MANAGE_USERS',
        'CREATE_USERS',
        'UPDATE_USERS',
        'DELETE_USERS',
        'MANAGE_SKILLS',
        'CREATE_SKILLS',
        'UPDATE_SKILLS',
        'DELETE_SKILLS',
        'MANAGE_CATEGORIES',
        'CREATE_CATEGORIES',
        'UPDATE_CATEGORIES',
        'DELETE_CATEGORIES',
        'MANAGE_MEMBERS',
        'CREATE_MEMBERS',
        'UPDATE_MEMBERS',
        'DELETE_MEMBERS',
        'FORCE_DELETE_MEMBERS',
        'RESTORE_MEMBERS',
        'MANAGE_MISSIONARIES',
        'CREATE_MISSIONARIES',
        'UPDATE_MISSIONARIES',
        'DELETE_MISSIONARIES',
        'FORCE_DELETE_MISSIONARIES',
        'RESTORE_MISSIONARIES',
    ]);

});

test('label return correct label', function (): void {

    expect(TenantPermission::UPDATE_REGULAR_TAG->label())->toBe(__('Update Regular Tag'));
    expect(TenantPermission::DELETE_REGULAR_TAG->label())->toBe(__('Delete Regular Tag'));
    expect(TenantPermission::CREATE_REGULAR_TAG->label())->toBe(__('Create Regular Tag'));
    expect(TenantPermission::MANAGE_USERS->label())->toBe(__('Manage Users'));
    expect(TenantPermission::CREATE_USERS->label())->toBe(__('Create User'));
    expect(TenantPermission::UPDATE_USERS->label())->toBe(__('Update User'));
    expect(TenantPermission::DELETE_USERS->label())->toBe(__('Delete User'));
    expect(TenantPermission::MANAGE_SKILLS->label())->toBe(__('Manage Skills'));
    expect(TenantPermission::CREATE_SKILLS->label())->toBe(__('Create Skill'));
    expect(TenantPermission::UPDATE_SKILLS->label())->toBe(__('Update Skill'));
    expect(TenantPermission::DELETE_SKILLS->label())->toBe(__('Delete Skill'));
    expect(TenantPermission::MANAGE_CATEGORIES->label())->toBe(__('Manage Categories'));
    expect(TenantPermission::CREATE_CATEGORIES->label())->toBe(__('Create Category'));
    expect(TenantPermission::UPDATE_CATEGORIES->label())->toBe(__('Update Category'));
    expect(TenantPermission::DELETE_CATEGORIES->label())->toBe(__('Delete Category'));
    expect(TenantPermission::MANAGE_MEMBERS->label())->toBe(__('Manage Members'));
    expect(TenantPermission::CREATE_MEMBERS->label())->toBe(__('Create Member'));
    expect(TenantPermission::UPDATE_MEMBERS->label())->toBe(__('Update Member'));
    expect(TenantPermission::DELETE_MEMBERS->label())->toBe(__('Delete Member'));
    expect(TenantPermission::FORCE_DELETE_MEMBERS->label())->toBe(__('Force Delete Member'));
    expect(TenantPermission::RESTORE_MEMBERS->label())->toBe(__('Restore Member'));
    expect(TenantPermission::MANAGE_MISSIONARIES->label())->toBe(__('Manage Missionaries'));
    expect(TenantPermission::CREATE_MISSIONARIES->label())->toBe(__('Create Missionary'));
    expect(TenantPermission::UPDATE_MISSIONARIES->label())->toBe(__('Update Missionary'));
    expect(TenantPermission::DELETE_MISSIONARIES->label())->toBe(__('Delete Missionary'));
    expect(TenantPermission::FORCE_DELETE_MISSIONARIES->label())->toBe(__('Force Delete Missionary'));
    expect(TenantPermission::RESTORE_MISSIONARIES->label())->toBe(__('Restore Missionary'));

});
