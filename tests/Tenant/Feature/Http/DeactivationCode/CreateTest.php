<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\DeactivationCode;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::DEACTIVATION_CODES_MANAGE, TenantPermission::DEACTIVATION_CODES_CREATE);
    });

    it('can be stored', function (): void {
        from(route('codes.deactivationCodes.index'))
            ->post(route('codes.deactivationCodes.store'), [
                'name' => 'Test Deactivation Code',
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('codes.deactivationCodes.index'));

        assertDatabaseCount('deactivation_codes', 1);

        $deactivationCode = DeactivationCode::latest()->first();

        expect($deactivationCode)->not->toBeNull()
            ->and($deactivationCode->name)->toBe('Test Deactivation Code');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot be stored', function (): void {
        from(route('codes.deactivationCodes.index'))
            ->post(route('codes.deactivationCodes.store'), [
                'name' => 'Test Deactivation Code',
            ])
            ->assertForbidden();

        assertDatabaseCount('deactivation_codes', 0);
    });
});
