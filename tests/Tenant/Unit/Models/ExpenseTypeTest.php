<?php

declare(strict_types=1);

use App\Models\ExpenseType;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $expenseType = ExpenseType::factory()->create()->fresh();

    expect(array_keys($expenseType->toArray()))->toBe([
        'id',
        'name',
        'default_amount',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $expenseType = ExpenseType::factory()->create([
        'name' => 'test expense type',
    ])->fresh();

    expect($expenseType->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($expenseType->updated_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($expenseType->name)->toBe('Test Expense Type'); // AsUcWords cast
});

it('can have a default amount', function (): void {
    $expenseType = ExpenseType::factory()->create([
        'default_amount' => 100.50,
    ]);

    expect($expenseType->default_amount)->toBe(100.5);
});

it('can have null default amount', function (): void {
    $expenseType = ExpenseType::factory()->create([
        'default_amount' => null,
    ]);

    expect($expenseType->default_amount)->toBeNull();
});
