<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\DeactivationCode;
use App\Models\Member;

use function Pest\Laravel\patch;

describe('if user has permission', function (): void {
    it('can activate an inactive member', function (): void {
        asUserWithPermission(TenantPermission::MEMBERS_ACTIVATE);

        $deactivationCode = DeactivationCode::factory()->create();
        $member = Member::factory()->inactive()->create([
            'deactivation_code_id' => $deactivationCode->id,
        ]);

        $response = patch(route('members.activate', $member));

        $response->assertOk();

        $member->refresh();
        expect($member->active)->toBeTrue();
        expect($member->deactivation_code_id)->toBeNull();
    });

    it('cannot activate an already active member', function (): void {
        asUserWithPermission(TenantPermission::MEMBERS_ACTIVATE);

        $member = Member::factory()->active()->create();

        $response = patch(route('members.activate', $member));

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['active']);
    });
});

describe('if user does not have permission', function (): void {
    it('cannot activate a member', function (): void {
        asUserWithoutPermission();

        $deactivationCode = DeactivationCode::factory()->create();
        $member = Member::factory()->inactive()->create([
            'deactivation_code_id' => $deactivationCode->id,
        ]);

        $response = patch(route('members.activate', $member));

        $response->assertForbidden();

        $member->refresh();
        expect($member->active)->toBeFalse();
        expect($member->deactivation_code_id)->toBe($deactivationCode->id);
    });
});
