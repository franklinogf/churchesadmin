<?php

declare(strict_types=1);

use App\Models\Address;

test('to array', function (): void {
    $address = Address::factory()->create()->fresh();
    expect(array_keys($address->toArray()))->toBe([
        'id',
        'addressable_type',
        'addressable_id',
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
