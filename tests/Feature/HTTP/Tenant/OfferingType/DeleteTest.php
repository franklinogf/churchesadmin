<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\OfferingType;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted if user has permission', function (): void {
    $offeringType = OfferingType::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_OFFERING_TYPES, TenantPermission::DELETE_OFFERING_TYPES)
        ->from(route('codes.offeringTypes.index'))
        ->delete(route('codes.offeringTypes.destroy', ['offeringType' => $offeringType]))
        ->assertRedirect(route('codes.offeringTypes.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('offering_types', 0);

    expect(OfferingType::all()->count())->toBe(0);
});

it('cannot be deleted if user does not have permission', function (): void {
    $offeringType = OfferingType::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_OFFERING_TYPES)
        ->from(route('codes.offeringTypes.index'))
        ->delete(route('codes.offeringTypes.destroy', ['offeringType' => $offeringType]))
        ->assertForbidden();

    assertDatabaseCount('offering_types', 1);

    expect(OfferingType::all()->count())->toBe(1);
});
