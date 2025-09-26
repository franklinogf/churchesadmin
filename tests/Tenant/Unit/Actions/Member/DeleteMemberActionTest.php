<?php

declare(strict_types=1);

use App\Actions\Member\DeleteMemberAction;
use App\Models\Address;
use App\Models\Member;

it('can delete a member', function (): void {
    $member = Member::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
    ]);

    $memberId = $member->id;

    $action = new DeleteMemberAction();
    $action->handle($member);

    expect(Member::find($memberId))->toBeNull();

});

it('can delete member with address', function (): void {
    $member = Member::factory()->hasAddress()->create();
    $memberId = $member->id;
    $addressId = $member->address->id;

    $action = new DeleteMemberAction();
    $action->handle($member);

    expect(Member::find($memberId))->toBeNull()
        ->and(Address::find($addressId))->toBeNull();
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
    expect(Member::find($memberId))->toBeNull();

});
