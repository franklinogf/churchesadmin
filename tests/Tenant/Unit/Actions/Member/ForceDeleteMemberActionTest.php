<?php

declare(strict_types=1);

use App\Actions\Member\ForceDeleteMemberAction;
use App\Models\Member;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

describe('ForceDeleteMemberAction', function () {
    it('can permanently delete a member', function (): void {
        $member = Member::factory()->create([
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        $memberId = $member->id;

        $action = new ForceDeleteMemberAction();
        $action->handle($member);

        // Member should be permanently deleted
        expect(Member::find($memberId))->toBeNull()
            ->and(Member::withTrashed()->find($memberId))->toBeNull();
    });

    it('can permanently delete a soft deleted member', function (): void {
        $member = Member::factory()->create();
        $memberId = $member->id;

        // First soft delete
        $member->delete();

        expect(Member::find($memberId))->toBeNull()
            ->and(Member::withTrashed()->find($memberId))->not->toBeNull();

        // Now force delete
        $trashedMember = Member::withTrashed()->find($memberId);
        $action = new ForceDeleteMemberAction();
        $action->handle($trashedMember);

        // Member should be permanently deleted
        expect(Member::find($memberId))->toBeNull()
            ->and(Member::withTrashed()->find($memberId))->toBeNull();
    });

    it('can permanently delete member with address', function (): void {
        $member = Member::factory()->hasAddress()->create();
        $memberId = $member->id;
        $addressId = $member->address->id;

        $action = new ForceDeleteMemberAction();
        $action->handle($member);

        // Member should be permanently deleted
        expect(Member::find($memberId))->toBeNull()
            ->and(Member::withTrashed()->find($memberId))->toBeNull();

        // Check that address cleanup happens through model relationships
        expect(App\Models\Address::find($addressId))->toBeNull();
    });

    it('can permanently delete member with tags', function (): void {
        $member = Member::factory()->create();

        // Attach some tags
        $member->attachTags(['Skill1', 'Skill2'], 'skill');
        $tagIds = $member->tags()->pluck('id')->toArray();

        expect($member->tags()->count())->toBeGreaterThan(0);

        $memberId = $member->id;

        $action = new ForceDeleteMemberAction();
        $action->handle($member);

        // Member should be permanently deleted
        expect(Member::find($memberId))->toBeNull()
            ->and(Member::withTrashed()->find($memberId))->toBeNull();

        // Tags should still exist (not cascade deleted)
        foreach ($tagIds as $tagId) {
            expect(App\Models\Tag::find($tagId))->not->toBeNull();
        }
    });
});
