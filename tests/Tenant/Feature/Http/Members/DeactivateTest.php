<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\DeactivationCode;
use App\Models\Member;

use function Pest\Laravel\patch;

describe('if user has permission', function (): void {

    it('can deactivate a member with a deactivation code', function (): void {
        asUserWithPermission(TenantPermission::MEMBERS_DEACTIVATE);

        $member = Member::factory()->active()->create();
        $deactivationCode = DeactivationCode::factory()->create([
            'name' => 'Moved Away',
        ]);

        $response = patch(route('members.deactivate', $member), [
            'deactivation_code_id' => $deactivationCode->id,
        ]);

        $response->assertRedirect();

        $member->refresh();
        expect($member->active)->toBeFalse();
        expect($member->deactivation_code_id)->toBe($deactivationCode->id);
    });

    it('requires a deactivation code', function (): void {
        asUserWithPermission(TenantPermission::MEMBERS_DEACTIVATE);

        $member = Member::factory()->active()->create();

        $response = patch(route('members.deactivate', $member), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['deactivation_code_id']);

        $member->refresh();
        expect($member->active)->toBeTrue();
    });

    it('requires a valid deactivation code', function (): void {
        asUserWithPermission(TenantPermission::MEMBERS_DEACTIVATE);

        $member = Member::factory()->active()->create();

        $response = patch(route('members.deactivate', $member), [
            'deactivation_code_id' => 999,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['deactivation_code_id']);

        $member->refresh();
        expect($member->active)->toBeTrue();
    });

});

describe('if user does not have permission', function (): void {
    it('cannot deactivate a member', function (): void {
        asUserWithoutPermission();

        $member = Member::factory()->active()->create();
        $deactivationCode = DeactivationCode::factory()->create();

        $response = patch(route('members.deactivate', $member), [
            'deactivation_code_id' => $deactivationCode->id,
        ]);

        $response->assertForbidden();

        $member->refresh();
        expect($member->active)->toBeTrue();
    });
});
