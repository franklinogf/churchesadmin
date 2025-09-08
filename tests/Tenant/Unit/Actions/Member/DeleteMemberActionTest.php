<?php

declare(strict_types=1);

use App\Actions\Member\DeleteMemberAction;
use App\Models\Member;

it('can soft delete a member', function (): void {
    $member = Member::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
    ]);

    $memberId = $member->id;

    $action = new DeleteMemberAction();
    $action->handle($member);

    // Member should be soft deleted
    expect(Member::find($memberId))->toBeNull()
        ->and(Member::withTrashed()->find($memberId))->not->toBeNull()
        ->and(Member::withTrashed()->find($memberId)->deleted_at)->not->toBeNull();
});

it('can delete member with address', function (): void {
    $member = Member::factory()->hasAddress()->create();
    $memberId = $member->id;
    $addressId = $member->address->id;

    $action = new DeleteMemberAction();
    $action->handle($member);

    // Member should be soft deleted
    expect(Member::find($memberId))->toBeNull()
        ->and(Member::withTrashed()->find($memberId))->not->toBeNull();

    // Address should still exist (not cascade deleted)
    expect($member->fresh(['address'])->address)->not->toBeNull();
});

it('can delete member with tags', function (): void {
    $member = Member::factory()->create();

    // Attach some tags
    $member->attachTags(['Skill1', 'Skill2'], 'skill');
    $member->attachTags(['Category1'], 'category');

    expect($member->tags()->count())->toBeGreaterThan(0);

    $memberId = $member->id;

    $action = new DeleteMemberAction();
    $action->handle($member);

    // Member should be soft deleted
    expect(Member::find($memberId))->toBeNull()
        ->and(Member::withTrashed()->find($memberId))->not->toBeNull();
});
