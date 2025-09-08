<?php

declare(strict_types=1);

use App\Actions\Member\UpdateMemberAction;
use App\Enums\TagType;
use App\Models\Member;

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

    // Attach old skill
    $member->attachTags(['Old Skill'], TagType::SKILL->value);

    $updateData = [
        'skills' => ['New Skill 1', 'New Skill 2'],
    ];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData);

    $memberSkills = $member->fresh()->tags()->where('type', TagType::SKILL->value)->pluck('name')->toArray();

    expect($memberSkills)->toContain('New Skill 1')
        ->and($memberSkills)->toContain('New Skill 2')
        ->and($memberSkills)->not->toContain('Old Skill');
});

it('can update member categories', function (): void {
    $member = Member::factory()->create();

    // Attach old category
    $member->attachTags(['Old Category'], TagType::CATEGORY->value);

    $updateData = [
        'categories' => ['New Category'],
    ];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData);

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
    $action->handle($member, $updateData, $addressData);

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
    $action->handle($member, $updateData, $addressData);

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
    $action->handle($member, $updateData, null);

    $member->refresh();

    expect($member->address)->toBeNull();
});

it('can update all data at once', function (): void {
    $member = Member::factory()->hasAddress()->create([
        'name' => 'Old Name',
    ]);

    $updateData = [
        'name' => 'Updated Name',
        'phone' => '+555-9999',
        'skills' => ['Updated Skill'],
        'categories' => ['Updated Category'],
    ];

    $addressData = [
        'address_1' => '789 Complete St',
        'city' => 'Complete City',
    ];

    $action = new UpdateMemberAction();
    $action->handle($member, $updateData, $addressData);

    $member->refresh();

    expect($member->name)->toBe('Updated Name')
        ->and($member->phone)->toBe('+555-9999')
        ->and($member->address->address_1)->toBe('789 Complete St');

    $memberSkills = $member->tags()->where('type', TagType::SKILL->value)->pluck('name')->toArray();
    $memberCategories = $member->tags()->where('type', TagType::CATEGORY->value)->pluck('name')->toArray();

    expect($memberSkills)->toContain('Updated Skill')
        ->and($memberCategories)->toContain('Updated Category');
});
