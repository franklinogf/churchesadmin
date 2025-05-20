<?php

declare(strict_types=1);

use App\Enums\TenantPermission;

it('has needed enums', function (): void {

    expect(TenantPermission::names())->toBe([
        'REGULAR_TAGS_UPDATE',
        'REGULAR_TAGS_DELETE',
        'REGULAR_TAGS_CREATE',
        'USERS_MANAGE',
        'USERS_CREATE',
        'USERS_UPDATE',
        'USERS_DELETE',
        'SKILLS_MANAGE',
        'SKILLS_CREATE',
        'SKILLS_UPDATE',
        'SKILLS_DELETE',
        'CATEGORIES_MANAGE',
        'CATEGORIES_CREATE',
        'CATEGORIES_UPDATE',
        'CATEGORIES_DELETE',
        'MEMBERS_MANAGE',
        'MEMBERS_CREATE',
        'MEMBERS_UPDATE',
        'MEMBERS_DELETE',
        'MEMBERS_FORCE_DELETE',
        'MEMBERS_RESTORE',
        'MISSIONARIES_MANAGE',
        'MISSIONARIES_CREATE',
        'MISSIONARIES_UPDATE',
        'MISSIONARIES_DELETE',
        'MISSIONARIES_FORCE_DELETE',
        'MISSIONARIES_RESTORE',
        'OFFERINGS_MANAGE',
        'OFFERINGS_CREATE',
        'OFFERINGS_UPDATE',
        'OFFERINGS_DELETE',
        'OFFERING_TYPES_MANAGE',
        'OFFERING_TYPES_CREATE',
        'OFFERING_TYPES_UPDATE',
        'OFFERING_TYPES_DELETE',
        'EXPENSE_TYPES_MANAGE',
        'EXPENSE_TYPES_CREATE',
        'EXPENSE_TYPES_UPDATE',
        'EXPENSE_TYPES_DELETE',
        'WALLETS_MANAGE',
        'WALLETS_CREATE',
        'WALLETS_UPDATE',
        'WALLETS_DELETE',
        'CHECK_LAYOUTS_MANAGE',
        'CHECK_LAYOUTS_CREATE',
        'CHECK_LAYOUTS_UPDATE',
        'CHECK_LAYOUTS_DELETE',
        'CHECKS_MANAGE',
        'CHECKS_CREATE',
        'CHECKS_UPDATE',
        'CHECKS_DELETE',
        'CHECKS_CONFIRM',
        'CHECKS_PRINT',

    ]);

});

test('label return correct label', function (): void {

    expect(TenantPermission::REGULAR_TAGS_UPDATE->label())->toBe(__('Update Regular Tag'));
    expect(TenantPermission::REGULAR_TAGS_DELETE->label())->toBe(__('Delete Regular Tag'));
    expect(TenantPermission::REGULAR_TAGS_CREATE->label())->toBe(__('Create Regular Tag'));
    expect(TenantPermission::USERS_MANAGE->label())->toBe(__('Manage Users'));
    expect(TenantPermission::USERS_CREATE->label())->toBe(__('Create User'));
    expect(TenantPermission::USERS_UPDATE->label())->toBe(__('Update User'));
    expect(TenantPermission::USERS_DELETE->label())->toBe(__('Delete User'));
    expect(TenantPermission::SKILLS_MANAGE->label())->toBe(__('Manage Skills'));
    expect(TenantPermission::SKILLS_CREATE->label())->toBe(__('Create Skill'));
    expect(TenantPermission::SKILLS_UPDATE->label())->toBe(__('Update Skill'));
    expect(TenantPermission::SKILLS_DELETE->label())->toBe(__('Delete Skill'));
    expect(TenantPermission::CATEGORIES_MANAGE->label())->toBe(__('Manage Categories'));
    expect(TenantPermission::CATEGORIES_CREATE->label())->toBe(__('Create Category'));
    expect(TenantPermission::CATEGORIES_UPDATE->label())->toBe(__('Update Category'));
    expect(TenantPermission::CATEGORIES_DELETE->label())->toBe(__('Delete Category'));
    expect(TenantPermission::MEMBERS_MANAGE->label())->toBe(__('Manage Members'));
    expect(TenantPermission::MEMBERS_CREATE->label())->toBe(__('Create Member'));
    expect(TenantPermission::MEMBERS_UPDATE->label())->toBe(__('Update Member'));
    expect(TenantPermission::MEMBERS_DELETE->label())->toBe(__('Delete Member'));
    expect(TenantPermission::MEMBERS_FORCE_DELETE->label())->toBe(__('Force Delete Member'));
    expect(TenantPermission::MEMBERS_RESTORE->label())->toBe(__('Restore Member'));
    expect(TenantPermission::MISSIONARIES_MANAGE->label())->toBe(__('Manage Missionaries'));
    expect(TenantPermission::MISSIONARIES_CREATE->label())->toBe(__('Create Missionary'));
    expect(TenantPermission::MISSIONARIES_UPDATE->label())->toBe(__('Update Missionary'));
    expect(TenantPermission::MISSIONARIES_DELETE->label())->toBe(__('Delete Missionary'));
    expect(TenantPermission::MISSIONARIES_FORCE_DELETE->label())->toBe(__('Force Delete Missionary'));
    expect(TenantPermission::MISSIONARIES_RESTORE->label())->toBe(__('Restore Missionary'));
    expect(TenantPermission::OFFERINGS_MANAGE->label())->toBe(__('Manage Offerings'));
    expect(TenantPermission::OFFERINGS_CREATE->label())->toBe(__('Create Offering'));
    expect(TenantPermission::OFFERINGS_UPDATE->label())->toBe(__('Update Offering'));
    expect(TenantPermission::OFFERINGS_DELETE->label())->toBe(__('Delete Offering'));
    expect(TenantPermission::OFFERING_TYPES_MANAGE->label())->toBe(__('Manage Offering Types'));
    expect(TenantPermission::OFFERING_TYPES_CREATE->label())->toBe(__('Create Offering Type'));
    expect(TenantPermission::OFFERING_TYPES_UPDATE->label())->toBe(__('Update Offering Type'));
    expect(TenantPermission::OFFERING_TYPES_DELETE->label())->toBe(__('Delete Offering Type'));
    expect(TenantPermission::EXPENSE_TYPES_MANAGE->label())->toBe(__('Manage Expense Types'));
    expect(TenantPermission::EXPENSE_TYPES_CREATE->label())->toBe(__('Create Expense Type'));
    expect(TenantPermission::EXPENSE_TYPES_UPDATE->label())->toBe(__('Update Expense Type'));
    expect(TenantPermission::EXPENSE_TYPES_DELETE->label())->toBe(__('Delete Expense Type'));
    expect(TenantPermission::WALLETS_MANAGE->label())->toBe(__('Manage Wallets'));
    expect(TenantPermission::WALLETS_CREATE->label())->toBe(__('Create Wallet'));
    expect(TenantPermission::WALLETS_UPDATE->label())->toBe(__('Update Wallet'));
    expect(TenantPermission::WALLETS_DELETE->label())->toBe(__('Delete Wallet'));
    expect(TenantPermission::CHECK_LAYOUTS_MANAGE->label())->toBe(__('Manage Check Layouts'));
    expect(TenantPermission::CHECK_LAYOUTS_CREATE->label())->toBe(__('Create Check Layout'));
    expect(TenantPermission::CHECK_LAYOUTS_UPDATE->label())->toBe(__('Update Check Layout'));
    expect(TenantPermission::CHECK_LAYOUTS_DELETE->label())->toBe(__('Delete Check Layout'));
    expect(TenantPermission::CHECKS_MANAGE->label())->toBe(__('Manage Checks'));
    expect(TenantPermission::CHECKS_CREATE->label())->toBe(__('Create Check'));
    expect(TenantPermission::CHECKS_UPDATE->label())->toBe(__('Update Check'));
    expect(TenantPermission::CHECKS_DELETE->label())->toBe(__('Delete Check'));
    expect(TenantPermission::CHECKS_CONFIRM->label())->toBe(__('Confirm Check'));
    expect(TenantPermission::CHECKS_PRINT->label())->toBe(__('Print Check'));
});
