<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Member;

use function Pest\Laravel\assertDatabaseCount;

it('can be restored if user has permission', function (): void {
    $member = Member::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_MEMBERS, TenantPermission::RESTORE_MEMBERS)
        ->put(route('members.restore', ['member' => $member]))
        ->assertRedirect(route('members.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('members', 1);

    expect(Member::all()->count())->toBe(1)
        ->and(Member::withTrashed()->count())->toBe(1);

});

it('cannot be restored if user does not have permission', function (): void {
    $member = Member::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_MEMBERS)
        ->put(route('members.restore', ['member' => $member]))
        ->assertForbidden();

    assertDatabaseCount('members', 1);

    expect(Member::all()->count())->toBe(0)
        ->and(Member::withTrashed()->count())->toBe(1);

});
