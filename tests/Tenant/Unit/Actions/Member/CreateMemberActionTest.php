<?php

declare(strict_types=1);

use App\Actions\Member\CreateMemberAction;
use App\Enums\TagType;
use App\Models\Member;

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

it('can create a member without email and phone', function (): void {
    $memberData = [
        'name' => 'NoContact',
        'last_name' => 'Person',
        'email' => null,
        'phone' => null,
        'dob' => '1985-06-15',
        'gender' => 'female',
        'civil_status' => 'single',
    ];

    $action = new CreateMemberAction();
    $member = $action->handle($memberData);

    expect($member)->not->toBeNull()
        ->and($member->name)->toBe('NoContact')
        ->and($member->last_name)->toBe('Person')
        ->and($member->email)->toBeNull()
        ->and($member->phone)->toBeNull()
        ->and($member->dob->toDateString())->toBe('1985-06-15');
});
it('can create a member with skills', function (): void {

    $memberData = [
        'name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'phone' => '+123456790',
        'gender' => 'female',
        'civil_status' => 'married',
        'skills' => ['Programming', 'Music'],
    ];

    $action = new CreateMemberAction();
    $action->handle($memberData);

    $member = Member::latest()->first();

    expect($member)->not->toBeNull();

    $memberSkills = $member->tags()->where('type', TagType::SKILL->value)->pluck('name')->toArray();
    expect($memberSkills)->toContain('Programming')
        ->and($memberSkills)->toContain('Music');
});
it('can create a member with categories', function (): void {

    $memberData = [
        'name' => 'Bob',
        'last_name' => 'Johnson',
        'email' => 'bob.johnson@example.com',
        'phone' => '+123456791',
        'gender' => 'male',
        'civil_status' => 'single',
        'categories' => ['Youth', 'Worship'],
    ];

    $action = new CreateMemberAction();
    $action->handle($memberData);

    $member = Member::latest()->first();

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
    $action->handle($memberData, $addressData);

    $member = Member::latest()->first();

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

    $memberData = [
        'name' => 'Complete',
        'last_name' => 'Member',
        'email' => 'complete.member@example.com',
        'phone' => '+555-0123',
        'gender' => 'male',
        'civil_status' => 'widowed',
        'skills' => ['Teaching'],
        'categories' => ['Adult'],
    ];

    $addressData = [
        'address_1' => '456 Oak Ave',
        'city' => 'Springfield',
        'state' => 'IL',
        'zip_code' => '62701',
        'country' => 'US',
    ];

    $action = new CreateMemberAction();
    $action->handle($memberData, $addressData);

    $member = Member::latest()->first();

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
