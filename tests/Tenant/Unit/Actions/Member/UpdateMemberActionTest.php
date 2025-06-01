<?php

declare(strict_types=1);

use App\Actions\Member\UpdateMemberAction;
use App\Enums\TagType;
use App\Models\Member;
use App\Models\Tag;

it('can update member basic data', function (): void {
    $member = Member::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
    ]);

    $updateData = [
        'name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
    ];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData);

    $member->refresh();

    expect($member->name)->toBe('Jane')
        ->and($member->last_name)->toBe('Smith')
        ->and($member->email)->toBe('jane.smith@example.com');
});

it('can update member skills', function (): void {
    $member = Member::factory()->create();

    $oldSkill = Tag::factory()->create(['type' => TagType::SKILL->value, 'name' => 'Old Skill']);
    $newSkill1 = Tag::factory()->create(['type' => TagType::SKILL->value, 'name' => 'New Skill 1']);
    $newSkill2 = Tag::factory()->create(['type' => TagType::SKILL->value, 'name' => 'New Skill 2']);

    // Attach old skill
    $member->attachTags([$oldSkill->name], TagType::SKILL->value);

    $updateData = [];
    $skills = [$newSkill1->name, $newSkill2->name];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData, $skills);

    $memberSkills = $member->fresh()->tags()->where('type', TagType::SKILL->value)->pluck('name')->toArray();

    expect($memberSkills)->toContain('New Skill 1')
        ->and($memberSkills)->toContain('New Skill 2')
        ->and($memberSkills)->not->toContain('Old Skill');
});

it('can update member categories', function (): void {
    $member = Member::factory()->create();

    $oldCategory = Tag::factory()->create(['type' => TagType::CATEGORY->value, 'name' => 'Old Category']);
    $newCategory = Tag::factory()->create(['type' => TagType::CATEGORY->value, 'name' => 'New Category']);

    // Attach old category
    $member->attachTags([$oldCategory->name], TagType::CATEGORY->value);

    $updateData = [];
    $categories = [$newCategory->name];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData, null, $categories);

    $memberCategories = $member->fresh()->tags()->where('type', TagType::CATEGORY->value)->pluck('name')->toArray();

    expect($memberCategories)->toContain('New Category')
        ->and($memberCategories)->not->toContain('Old Category');
});

it('can create address when member has none', function (): void {
    $member = Member::factory()->create();

    $updateData = [];
    $addressData = [
        'address_1' => '123 New St',
        'city' => 'New City',
        'state' => 'NY',
        'zip_code' => '10001',
        'country' => 'US',
    ];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData, null, null, $addressData);

    $member->refresh();

    expect($member->address)->not->toBeNull()
        ->and($member->address->address_1)->toBe('123 New St')
        ->and($member->address->city)->toBe('New City');
});

it('can update existing address', function (): void {
    $member = Member::factory()->hasAddress()->create();
    $originalAddress = $member->address->address_1;

    $updateData = [];
    $addressData = [
        'address_1' => '456 Updated Ave',
        'city' => 'Updated City',
    ];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData, null, null, $addressData);

    $member->refresh();

    expect($member->address->address_1)->toBe('456 Updated Ave')
        ->and($member->address->address_1)->not->toBe($originalAddress)
        ->and($member->address->city)->toBe('Updated City');
});

it('can delete address when set to null', function (): void {
    $member = Member::factory()->hasAddress()->create();

    expect($member->address)->not->toBeNull();

    $updateData = [];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData, null, null, null);

    $member->refresh();

    expect($member->address)->toBeNull();
});

it('can update all data at once', function (): void {
    $member = Member::factory()->hasAddress()->create([
        'name' => 'Old Name',
    ]);

    $skill = Tag::factory()->create(['type' => TagType::SKILL->value, 'name' => 'Updated Skill']);
    $category = Tag::factory()->create(['type' => TagType::CATEGORY->value, 'name' => 'Updated Category']);

    $updateData = [
        'name' => 'Updated Name',
        'phone' => '+555-9999',
    ];
    $skills = [$skill->name];
    $categories = [$category->name];
    $addressData = [
        'address_1' => '789 Complete St',
        'city' => 'Complete City',
    ];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData, $skills, $categories, $addressData);

    $member->refresh();

    expect($member->name)->toBe('Updated Name')
        ->and($member->phone)->toBe('+555-9999')
        ->and($member->address->address_1)->toBe('789 Complete St');

    $memberSkills = $member->tags()->where('type', TagType::SKILL->value)->pluck('name')->toArray();
    $memberCategories = $member->tags()->where('type', TagType::CATEGORY->value)->pluck('name')->toArray();

    expect($memberSkills)->toContain('Updated Skill')
        ->and($memberCategories)->toContain('Updated Category');
});
