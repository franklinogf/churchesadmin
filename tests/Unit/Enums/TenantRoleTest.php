<?php

declare(strict_types=1);

use App\Enums\TenantRole;

it('has needed enums', function (): void {

    expect(TenantRole::names())->toBe([
        'SUPER_ADMIN',
        'ADMIN',
        'SECRETARY',
        'NO_ROLE',
    ]);

});

test('label return correct label', function (): void {

    expect(TenantRole::SUPER_ADMIN->label())->toBe(__('Super Admin'));
    expect(TenantRole::ADMIN->label())->toBe(__('Admin'));
    expect(TenantRole::SECRETARY->label())->toBe(__('Secretary'));
    expect(TenantRole::NO_ROLE->label())->toBe(__('No Role'));

});
