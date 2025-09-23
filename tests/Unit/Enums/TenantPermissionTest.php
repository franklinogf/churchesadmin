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
        'DEACTIVATION_CODES_MANAGE',
        'DEACTIVATION_CODES_CREATE',
        'DEACTIVATION_CODES_UPDATE',
        'DEACTIVATION_CODES_DELETE',
        'WALLETS_MANAGE',
        'WALLETS_CREATE',
        'WALLETS_UPDATE',
        'WALLETS_DELETE',
        'WALLETS_RESTORE',
        'WALLETS_CHECK_LAYOUT_UPDATE',
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
        'EMAILS_MANAGE',
        'EMAILS_CREATE',
        'EMAILS_UPDATE',
        'EMAILS_DELETE',
        'EMAILS_SEND',
        'EMAILS_SEND_TO_MEMBERS',
        'EMAILS_SEND_TO_MISSIONARIES',
        'VISITS_MANAGE',
        'VISITS_CREATE',
        'VISITS_UPDATE',
        'VISITS_DELETE',
        'VISITS_FORCE_DELETE',
        'VISITS_RESTORE',
        'EXPENSES_MANAGE',
        'EXPENSES_CREATE',
        'EXPENSES_UPDATE',
        'EXPENSES_DELETE',

    ]);

});

test('label return correct label', function (): void {

    collect(TenantPermission::cases())->each(function (TenantPermission $case): void {
        expect($case->label())->toBe(__('enum.tenant_permission.'.$case->value));
    });
});
