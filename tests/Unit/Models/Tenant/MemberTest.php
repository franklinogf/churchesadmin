<?php

declare(strict_types=1);

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Models\Address;
use App\Models\Member;

test('to array', function (): void {
    $member = Member::factory()->create()->fresh();

    expect(array_keys($member->toArray()))->toBe([
        'id',
        'name',
        'last_name',
        'email',
        'phone',
        'gender',
        'dob',
        'civil_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $member = Member::factory()->create()->fresh();

    expect($member->gender)->toBeInstanceOf(Gender::class);
    expect($member->dob)->toBeInstanceOf(Carbon\CarbonImmutable::class);
    expect($member->civil_status)->toBeInstanceOf(CivilStatus::class);
});

it('has an address', function (): void {
    $member = Member::factory()
        ->has(Address::factory())->create()->fresh();

    expect($member->address)->toBeInstanceOf(Address::class);
    expect($member->address->owner_id)->toBe($member->id);
    expect($member->address->owner_type)->toBe($member->getMorphClass());

});

it('can have tags', function (): void {
    $member = Member::factory()
        ->hasAttached(
            App\Models\Tag::factory()->count(2)
        )->create();

    expect($member->tags)->toHaveCount(2);
    expect($member->tags[0])->toBeInstanceOf(App\Models\Tag::class);
    expect($member->tags[1])->toBeInstanceOf(App\Models\Tag::class);
});
