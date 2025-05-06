<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use App\Models\Address;
use App\Models\Missionary;

test('to array', function (): void {
    $missionary = Missionary::factory()->create()->fresh();

    expect(array_keys($missionary->toArray()))->toBe([
        'id',
        'name',
        'last_name',
        'email',
        'phone',
        'gender',
        'church',
        'offering',
        'offering_frequency',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});

test('casts are applied correctly', function (): void {

    $missionary = Missionary::factory()->create(
        [
            'offering_frequency' => OfferingFrequency::MONTHLY,
            'gender' => Gender::MALE,
        ]
    )->fresh();
    expect($missionary->gender)->toBeInstanceOf(Gender::class);
    expect($missionary->offering_frequency)->toBeInstanceOf(OfferingFrequency::class);
});

it('has an address', function (): void {
    $missionary = Missionary::factory()
        ->has(Address::factory())->create();

    expect($missionary->address)->toBeInstanceOf(Address::class);
    expect($missionary->address->owner_id)->toBe($missionary->id);
    expect($missionary->address->owner_type)->toBe($missionary->getMorphClass());

});
