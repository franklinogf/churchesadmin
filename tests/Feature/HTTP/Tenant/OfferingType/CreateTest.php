<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\OfferingType;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_OFFERING_TYPES, TenantPermission::CREATE_OFFERING_TYPES);
    });

    it('can be stored', function (): void {
        from(route('codes.offeringTypes.index'))
            ->post(route('codes.offeringTypes.store'), [
                'name' => 'Test Offering Type',
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('codes.offeringTypes.index'));

        assertDatabaseCount('offering_types', 1);

        $offeringType = OfferingType::latest()->first();

        expect($offeringType)->not->toBeNull()
            ->and($offeringType->name)->toBe('Test Offering Type');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_OFFERING_TYPES);
    });

    it('cannot be stored', function (): void {
        from(route('codes.offeringTypes.index'))
            ->post(route('codes.offeringTypes.store'), [
                'name' => 'Test Offering Type',
            ])
            ->assertForbidden();

        assertDatabaseCount('offering_types', 0);
    });
});
