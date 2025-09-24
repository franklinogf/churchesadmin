<?php

declare(strict_types=1);

use App\Models\Address;
use App\Models\Member;
use App\Models\Missionary;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $address = Address::factory()->create()->fresh();
    expect(array_keys($address->toArray()))->toBe([
        'id',
        'owner_type',
        'owner_id',
        'address_1',
        'address_2',
        'city',
        'state',
        'country',
        'zip_code',
        'created_at',
        'updated_at',

    ]);
});

test('casts are applied correctly', function (): void {
    $address = Address::factory()->create()->fresh();

    expect($address->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($address->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('can have a member owner', function (): void {
    $address = Address::factory()->forMember()
        ->create()->fresh();

    expect($address->owner->id)->toBe($address->owner_id)
        ->and($address->owner->getMorphClass())->toBe($address->owner_type)
        ->and($address->owner)->toBeInstanceOf(Member::class);
});

it('can have a missionary owner', function (): void {
    $address = Address::factory()->forMissionary()
        ->create()->fresh();

    expect($address->owner->id)->toBe($address->owner_id)
        ->and($address->owner->getMorphClass())->toBe($address->owner_type)
        ->and($address->owner)->toBeInstanceOf(Missionary::class);
});

it('can have optional address fields', function (): void {
    $address = Address::factory()->create([
        'address_2' => null,
        // Note: country is required field, so we don't test null country
    ]);

    expect($address->address_2)->toBeNull();
    expect($address->country)->not->toBeNull();
});

it('requires owner relationship', function (): void {
    $address = Address::factory()->create();

    expect($address->owner_id)->not->toBeNull();
    expect($address->owner_type)->not->toBeNull();
    expect($address->owner)->not->toBeNull();
});

it('can handle polymorphic owner relationship', function (): void {
    $memberAddress = Address::factory()->forMember()->create();
    $missionaryAddress = Address::factory()->forMissionary()->create();

    expect($memberAddress->owner)->toBeInstanceOf(Member::class);
    expect($missionaryAddress->owner)->toBeInstanceOf(Missionary::class);
});
