<?php

declare(strict_types=1);

use App\Models\Offering;
use App\Models\OfferingType;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $offeringType = OfferingType::factory()->create()->fresh();

    expect(array_keys($offeringType->toArray()))->toBe([
        'id',
        'name',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $offeringType = OfferingType::factory()->create([
        'name' => 'test offering type',
    ])->fresh();

    expect($offeringType->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($offeringType->updated_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($offeringType->name)->toBe('Test Offering Type'); // AsUcWords cast
});

it('can have offerings', function (): void {
    $offeringType = OfferingType::factory()
        ->has(Offering::factory()->count(3), 'offerings')
        ->create();

    expect($offeringType->offerings)->toHaveCount(3);
    expect($offeringType->offerings[0])->toBeInstanceOf(Offering::class);
});

it('can have no offerings', function (): void {
    $offeringType = OfferingType::factory()->create();

    expect($offeringType->offerings)->toHaveCount(0);
});
