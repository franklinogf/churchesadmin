<?php

declare(strict_types=1);

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Models\Address;
use App\Models\Email;
use App\Models\Member;
use App\Models\Offering;
use App\Models\Tag;
use Carbon\CarbonImmutable;

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
        'baptism_date',
        'civil_status',
        'active',
        'created_at',
        'updated_at',
        'visit_id',
        'deactivation_code_id',
    ]);
});

test('casts are applied correctly', function (): void {
    $member = Member::factory()
        ->create([
            'gender' => Gender::MALE->value,
            'dob' => '1992-03-15',
            'baptism_date' => '2005-07-20',
            'civil_status' => CivilStatus::SINGLE->value,
        ])
        ->fresh();

    expect($member->gender)->toBeInstanceOf(Gender::class);
    expect($member->dob)->toBeInstanceOf(CarbonImmutable::class);
    expect($member->baptism_date)->toBeInstanceOf(CarbonImmutable::class);
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
            Tag::factory()->count(2)
        )->create();

    expect($member->tags)->toHaveCount(2);
    expect($member->tags[0])->toBeInstanceOf(Tag::class);
    expect($member->tags[1])->toBeInstanceOf(Tag::class);
});

it('can be active or inactive', function (): void {
    $activeMember = Member::factory()->active()->create();
    $inactiveMember = Member::factory()->inactive()->create();

    expect($activeMember->active)->toBeTrue();
    expect($inactiveMember->active)->toBeFalse()->and(
        $inactiveMember->deactivation_code_id
    )->not->toBeNull();
});

it('can have emails', function (): void {
    $member = Member::factory()
        ->has(Email::factory()->count(3), 'emails')
        ->create();

    expect($member->emails)->toHaveCount(3);
    expect($member->emails[0])->toBeInstanceOf(Email::class);
    expect($member->emails[1])->toBeInstanceOf(Email::class);
    expect($member->emails[2])->toBeInstanceOf(Email::class);
});

it('can have contributions', function (): void {
    $member = Member::factory()
        ->has(Offering::factory()->count(4), 'contributions')
        ->create();

    expect($member->contributions)->toHaveCount(4);
    expect($member->contributions[0])->toBeInstanceOf(Offering::class);
    expect($member->contributions[1])->toBeInstanceOf(Offering::class);
    expect($member->contributions[2])->toBeInstanceOf(Offering::class);
    expect($member->contributions[3])->toBeInstanceOf(Offering::class);
});

it('can have previous year contributions', function (): void {
    $member = Member::factory()
        ->has(Offering::factory()->prevYear()->count(2), 'previousYearContributions')
        ->create();

    expect($member->previousYearContributions)->toHaveCount(2);
    expect($member->previousYearContributions[0])->toBeInstanceOf(Offering::class);
    expect($member->previousYearContributions[1])->toBeInstanceOf(Offering::class);
});
