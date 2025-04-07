<?php

declare(strict_types=1);

use App\Models\Member;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can be deleted', function (): void {
    $member = Member::factory()->create()->fresh();
    actingAs(User::factory()->create())
        ->delete(route('members.destroy', ['member' => $member]))
        ->assertRedirect(route('members.index'));

    expect(Member::find($member->id))->toBeNull();
    expect(Member::withTrashed()->find($member->id))->not->toBeNull()
        ->and(Member::withTrashed()->find($member->id)->deleted_at)->not->toBeNull();
});
