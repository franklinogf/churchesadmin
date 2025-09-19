<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\DeactivationCode;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted if user has permission', function (): void {
    $deactivationCode = DeactivationCode::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::DEACTIVATION_CODES_MANAGE, TenantPermission::DEACTIVATION_CODES_DELETE)
        ->from(route('codes.deactivationCodes.index'))
        ->delete(route('codes.deactivationCodes.destroy', ['deactivationCode' => $deactivationCode]))
        ->assertRedirect(route('codes.deactivationCodes.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('deactivation_codes', 0);

    expect(DeactivationCode::all()->count())->toBe(0);
});

it('cannot be deleted if user does not have permission', function (): void {
    $deactivationCode = DeactivationCode::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::DEACTIVATION_CODES_MANAGE)
        ->from(route('codes.deactivationCodes.index'))
        ->delete(route('codes.deactivationCodes.destroy', ['deactivationCode' => $deactivationCode]))
        ->assertForbidden();

    assertDatabaseCount('deactivation_codes', 1);

    expect(DeactivationCode::all()->count())->toBe(1);
});
