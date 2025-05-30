<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use App\Models\Address;
use App\Models\Missionary;
use App\Models\Offering;

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

    expect($missionary->address)->toBeInstanceOf(Address::class)
        ->and($missionary->address->owner_id)->toBe($missionary->id)
        ->and($missionary->address->owner_type)->toBe($missionary->getMorphClass());

});

it('has offerings', function (): void {
    $missionary = Missionary::factory()
        ->has(Offering::factory()->count(2))->create();

    expect($missionary->offerings)->toHaveCount(2);

});
