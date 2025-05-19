<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Member;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted permanently if user has permission', function (): void {
    $member = Member::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MEMBERS_MANAGE, TenantPermission::MEMBERS_FORCE_DELETE)
        ->from(route('members.index'))
        ->delete(route('members.forceDelete', ['member' => $member]))
        ->assertRedirect(route('members.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('members', 0);

    expect(Member::all()->count())->toBe(0)
        ->and(Member::withTrashed()->count())->toBe(0);

});

it('cannot be deleted permanently if user does not have permission', function (): void {
    $member = Member::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MEMBERS_MANAGE)
        ->from(route('members.index'))
        ->delete(route('members.forceDelete', ['member' => $member]))
        ->assertForbidden();

    assertDatabaseCount('members', 1);

    expect(Member::all()->count())->toBe(0)
        ->and(Member::withTrashed()->count())->toBe(1);

});
