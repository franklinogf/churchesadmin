<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Member;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted if user has permission', function (): void {
    $member = Member::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_MEMBERS, TenantPermission::DELETE_MEMBERS)
        ->from(route('members.index'))
        ->delete(route('members.destroy', ['member' => $member]))
        ->assertRedirect(route('members.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('members', 1);

    expect(Member::all()->count())->toBe(0)
        ->and(Member::withTrashed()->count())->toBe(1);

});

it('cannot be deleted if user does not have permission', function (): void {
    $member = Member::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_MEMBERS)
        ->from(route('members.index'))
        ->delete(route('members.destroy', ['member' => $member]))
        ->assertRedirect(route('members.index'))
        ->assertSessionHas(FlashMessageKey::ERROR->value);

    assertDatabaseCount('members', 1);

    expect(Member::all()->count())->toBe(1);

});
