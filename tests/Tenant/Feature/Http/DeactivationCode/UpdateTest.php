<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\DeactivationCode;

use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::DEACTIVATION_CODES_MANAGE, TenantPermission::DEACTIVATION_CODES_UPDATE);
    });

    it('can be updated', function (): void {
        $deactivationCode = DeactivationCode::factory()->create([
            'name' => 'Test Deactivation Code',
        ]);

        from(route('codes.deactivationCodes.index'))
            ->put(route('codes.deactivationCodes.update', ['deactivationCode' => $deactivationCode]), [
                'name' => 'Updated Deactivation Code',
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('codes.deactivationCodes.index'));

        $updatedDeactivationCode = DeactivationCode::latest()->first();

        expect($updatedDeactivationCode)->not->toBeNull()
            ->and($updatedDeactivationCode->name)->toBe('Updated Deactivation Code');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::DEACTIVATION_CODES_MANAGE);
    });

    it('cannot be updated', function (): void {
        $deactivationCode = DeactivationCode::factory()->create([
            'name' => 'Test Deactivation Code',
        ]);

        from(route('codes.deactivationCodes.index'))
            ->put(route('codes.deactivationCodes.update', ['deactivationCode' => $deactivationCode]), [
                'name' => 'Updated Deactivation Code',
            ])
            ->assertForbidden();

        $deactivationCode->refresh();

        expect($deactivationCode->name)->toBe('Test Deactivation Code');
    });
});
