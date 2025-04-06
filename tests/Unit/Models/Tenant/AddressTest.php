<?php

declare(strict_types=1);

use App\Models\Address;

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
        'postal_code',
        'created_at',
        'updated_at',

    ]);
});

it('can have a member owner', function (): void {
    $address = Address::factory()->forMember()
        ->create()->fresh();

    expect($address->owner)->toBeInstanceOf(App\Models\Member::class);
    expect($address->owner->id)->toBe($address->owner_id);
    expect($address->owner->getMorphClass())->toBe($address->owner_type);
});

it('can have a missionary owner', function (): void {
    $address = Address::factory()->forMissionary()
        ->create()->fresh();

    expect($address->owner)->toBeInstanceOf(App\Models\Missionary::class);
    expect($address->owner->id)->toBe($address->owner_id);
    expect($address->owner->getMorphClass())->toBe($address->owner_type);
});
