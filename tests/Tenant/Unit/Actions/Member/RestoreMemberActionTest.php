<?php

declare(strict_types=1);

use App\Actions\Member\RestoreMemberAction;
use App\Models\Member;

it('can restore a soft deleted member', function (): void {
    $member = Member::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
    ]);

    $memberId = $member->id;

    // First soft delete the member
    $member->delete();

    expect(Member::find($memberId))->toBeNull()
        ->and(Member::withTrashed()->find($memberId))->not->toBeNull();

    // Now restore it
    $trashedMember = Member::withTrashed()->where('id', $memberId)->first();
    $action = new RestoreMemberAction();
    $action->handle($trashedMember);

    // Member should be restored
    expect(Member::find($memberId))->not->toBeNull()
        ->and(Member::find($memberId)->deleted_at)->toBeNull()
        ->and(Member::find($memberId)->name)->toBe('John')
        ->and(Member::find($memberId)->last_name)->toBe('Doe');
});

it('can restore member with relationships intact', function (): void {
    $member = Member::factory()->hasAddress()->create();
    $memberId = $member->id;
    $originalAddressId = $member->address->id;

    // Attach some tags
    $member->attachTags(['Skill1'], 'skill');
    $originalTagCount = $member->tags()->count();
    // Soft delete the member
    $member->delete();

    expect(Member::find($memberId))->toBeNull();

    // Restore the member
    $trashedMember = Member::withTrashed()->where('id', $memberId)->first();

    $action = new RestoreMemberAction();
    $action->handle($trashedMember);

    $restoredMember = Member::where('id', $memberId)->first();
    // Member and relationships should be restored
    expect($restoredMember)->not->toBeNull()
        ->and($restoredMember->address)->not->toBeNull()
        ->and($restoredMember->address->id)->toBe($originalAddressId)
        ->and($restoredMember->tags()->count())->toBe($originalTagCount);
});
