<?php

declare(strict_types=1);

use App\Actions\Member\CreateMemberAction;
use App\Enums\TagType;
use App\Models\Member;
use App\Models\Tag;

it('can create a member with basic data', function (): void {
    $memberData = [
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'phone' => '+123456789',
        'dob' => '1990-01-01',
        'gender' => 'male',
        'civil_status' => 'single',
    ];

    $action = new CreateMemberAction();
    $action->handle($memberData);

    $member = Member::where('email', 'john.doe@example.com')->first();

    expect($member)->not->toBeNull()
        ->and($member->name)->toBe('John')
        ->and($member->last_name)->toBe('Doe')
        ->and($member->email)->toBe('john.doe@example.com')
        ->and($member->phone)->toBe('+123456789')
        ->and($member->dob->toDateString())->toBe('1990-01-01');
});
it('can create a member with skills', function (): void {
    $skillTag1 = Tag::factory()->create(['type' => TagType::SKILL->value, 'name' => 'Programming', 'is_regular' => true]);
    $skillTag2 = Tag::factory()->create(['type' => TagType::SKILL->value, 'name' => 'Music', 'is_regular' => true]);

    $memberData = [
        'name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'phone' => '+123456790',
        'gender' => 'female',
        'civil_status' => 'married',
    ];

    $skills = [$skillTag1->name, $skillTag2->name];

    $action = new CreateMemberAction();
    $action->handle($memberData, $skills);

    $member = Member::where('email', 'jane.smith@example.com')->first();

    expect($member)->not->toBeNull();

    $memberSkills = $member->tags()->where('type', TagType::SKILL->value)->pluck('name')->toArray();
    expect($memberSkills)->toContain('Programming')
        ->and($memberSkills)->toContain('Music');
});
it('can create a member with categories', function (): void {
    $categoryTag1 = Tag::factory()->create(['type' => TagType::CATEGORY->value, 'name' => 'Youth', 'is_regular' => true]);
    $categoryTag2 = Tag::factory()->create(['type' => TagType::CATEGORY->value, 'name' => 'Worship', 'is_regular' => true]);

    $memberData = [
        'name' => 'Bob',
        'last_name' => 'Johnson',
        'email' => 'bob.johnson@example.com',
        'phone' => '+123456791',
        'gender' => 'male',
        'civil_status' => 'single',
    ];

    $categories = [$categoryTag1->name, $categoryTag2->name];

    $action = new CreateMemberAction();
    $action->handle($memberData, null, $categories);

    $member = Member::where('email', 'bob.johnson@example.com')->first();

    expect($member)->not->toBeNull();

    $memberCategories = $member->tags()->where('type', TagType::CATEGORY->value)->pluck('name')->toArray();
    expect($memberCategories)->toContain('Youth')
        ->and($memberCategories)->toContain('Worship');
});
it('can create a member with address', function (): void {
    $memberData = [
        'name' => 'Alice',
        'last_name' => 'Williams',
        'email' => 'alice.williams@example.com',
        'phone' => '+123456792',
        'gender' => 'female',
        'civil_status' => 'divorced',
    ];

    $addressData = [
        'address_1' => '123 Main St',
        'address_2' => 'Apt 4B',
        'city' => 'Anytown',
        'state' => 'CA',
        'zip_code' => '12345',
        'country' => 'US',
    ];

    $action = new CreateMemberAction();
    $action->handle($memberData, null, null, $addressData);

    $member = Member::where('email', 'alice.williams@example.com')->first();

    expect($member)->not->toBeNull()
        ->and($member->address)->not->toBeNull()
        ->and($member->address->address_1)->toBe('123 Main St')
        ->and($member->address->address_2)->toBe('Apt 4B')
        ->and($member->address->city)->toBe('Anytown')
        ->and($member->address->state)->toBe('CA')
        ->and($member->address->zip_code)->toBe('12345')
        ->and($member->address->country)->toBe('US');
});
it('can create a member with all optional data', function (): void {
    $skillTag = Tag::factory()->create(['type' => TagType::SKILL->value, 'name' => 'Teaching', 'is_regular' => true]);
    $categoryTag = Tag::factory()->create(['type' => TagType::CATEGORY->value, 'name' => 'Adult', 'is_regular' => true]);

    $memberData = [
        'name' => 'Complete',
        'last_name' => 'Member',
        'email' => 'complete.member@example.com',
        'phone' => '+555-0123',
        'gender' => 'male',
        'civil_status' => 'widowed',
    ];

    $skills = [$skillTag->name];
    $categories = [$categoryTag->name];
    $addressData = [
        'address_1' => '456 Oak Ave',
        'city' => 'Springfield',
        'state' => 'IL',
        'zip_code' => '62701',
        'country' => 'US',
    ];

    $action = new CreateMemberAction();
    $action->handle($memberData, $skills, $categories, $addressData);

    $member = Member::where('email', 'complete.member@example.com')->first();

    expect($member)->not->toBeNull()
        ->and($member->name)->toBe('Complete')
        ->and($member->last_name)->toBe('Member')
        ->and($member->address)->not->toBeNull()
        ->and($member->address->address_1)->toBe('456 Oak Ave');

    $memberSkills = $member->tags()->where('type', TagType::SKILL->value)->pluck('name')->toArray();
    $memberCategories = $member->tags()->where('type', TagType::CATEGORY->value)->pluck('name')->toArray();

    expect($memberSkills)->toContain('Teaching')
        ->and($memberCategories)->toContain('Adult');
});
