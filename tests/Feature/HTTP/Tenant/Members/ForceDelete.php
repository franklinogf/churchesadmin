<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Member;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted permanently if user has permission', function (): void {
    $member = Member::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_MEMBERS, TenantPermission::FORCE_DELETE_MEMBERS)
        ->delete(route('members.forceDelete', ['member' => $member]))
        ->assertRedirect(route('members.index'));

    assertDatabaseCount('members', 0);

    expect(Member::all()->count())->toBe(0)
        ->and(Member::withTrashed()->count())->toBe(0);

});

it('cannot be deleted permanently if user does not have permission', function (): void {
    $member = Member::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_MEMBERS)
        ->delete(route('members.forceDelete', ['member' => $member]))
        ->assertRedirect(route('members.index'))
        ->assertSessionHas(FlashMessageKey::ERROR->value);

    assertDatabaseCount('members', 1);

    expect(Member::all()->count())->toBe(0)
        ->and(Member::withTrashed()->count())->toBe(1);

});
