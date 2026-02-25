<?php

declare(strict_types=1);

use App\Models\CurrentYear;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

test('to array', function (): void {
    $currentYear = CurrentYear::factory()->create()->fresh();

    expect(array_keys($currentYear->toArray()))->toBe([
        'id',
        'year',
        'start_date',
        'end_date',
        'is_current',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $currentYear = CurrentYear::factory()->create()->fresh();

    expect($currentYear->is_current)->toBeBool();
    expect($currentYear->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($currentYear->updated_at)->toBeInstanceOf(CarbonImmutable::class);

    if ($currentYear->start_date) {
        expect($currentYear->start_date)->toBeInstanceOf(CarbonImmutable::class);
    }

    if ($currentYear->end_date) {
        expect($currentYear->end_date)->toBeInstanceOf(CarbonImmutable::class);
    }
});

it('can find current year', function (): void {
    // Clear any existing current years first
    CurrentYear::query()->delete();

    CurrentYear::factory()->create(['is_current' => false]);
    $currentYear = CurrentYear::factory()->create(['is_current' => true]);

    $found = CurrentYear::current();

    expect($found->id)->toBe($currentYear->id);
    expect($found->is_current)->toBeTrue();
});

it('throws exception when no current year exists', function (): void {
    // Clear any existing current years first
    CurrentYear::query()->delete();

    CurrentYear::factory()->create(['is_current' => false]);

    expect(fn (): CurrentYear => CurrentYear::current())
        ->toThrow(ModelNotFoundException::class);
});

it('can find previous year', function (): void {
    // Clear any existing years first
    CurrentYear::query()->delete();

    CurrentYear::factory()->create(['year' => '2024', 'is_current' => true]);
    $previousYear = CurrentYear::factory()->create(['year' => '2023', 'is_current' => false]);

    $foundPrevious = CurrentYear::previous();

    expect($foundPrevious->id)->toBe($previousYear->id);
    expect($foundPrevious->year)->toBe('2023');
});
