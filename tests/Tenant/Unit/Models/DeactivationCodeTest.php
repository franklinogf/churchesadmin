<?php

declare(strict_types=1);

use App\Models\DeactivationCode;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $deactivationCode = DeactivationCode::factory()->create()->fresh();

    expect(array_keys($deactivationCode->toArray()))->toBe([
        'id',
        'name',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $deactivationCode = DeactivationCode::factory()->create([
        'name' => 'test deactivation code',
    ])->fresh();

    expect($deactivationCode->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($deactivationCode->updated_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($deactivationCode->name)->toBe('Test Deactivation Code'); // AsUcWords cast
});
