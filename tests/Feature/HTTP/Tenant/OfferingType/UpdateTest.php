<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\OfferingType;

use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::OFFERING_TYPES_MANAGE, TenantPermission::OFFERING_TYPES_UPDATE);
    });

    it('can be updated', function (): void {
        $offeringType = OfferingType::factory()->create([
            'name' => 'Test Offering Type',
        ]);

        from(route('codes.offeringTypes.index'))
            ->put(route('codes.offeringTypes.update', ['offeringType' => $offeringType]), [
                'name' => 'Updated Offering Type',
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('codes.offeringTypes.index'));

        $updatedOfferingType = OfferingType::latest()->first();

        expect($updatedOfferingType)->not->toBeNull()
            ->and($updatedOfferingType->name)->toBe('Updated Offering Type');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::OFFERING_TYPES_MANAGE);
    });

    it('cannot be updated', function (): void {
        $offeringType = OfferingType::factory()->create([
            'name' => 'Test Offering Type',
        ]);

        from(route('codes.offeringTypes.index'))
            ->put(route('codes.offeringTypes.update', ['offeringType' => $offeringType]), [
                'name' => 'Updated Offering Type',
            ])
            ->assertForbidden();

        $offeringType->refresh();

        expect($offeringType->name)->toBe('Test Offering Type');
    });
});
