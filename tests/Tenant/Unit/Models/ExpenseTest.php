<?php

declare(strict_types=1);

use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Member;
use Bavix\Wallet\Models\Transaction;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $expense = Expense::factory()->create()->fresh();

    expect(array_keys($expense->toArray()))->toBe([
        'id',
        'transaction_id',
        'expense_type_id',
        'member_id',
        'date',
        'note',
        'current_year_id',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $expense = Expense::factory()->create()->fresh();

    expect($expense->date)->toBeInstanceOf(CarbonImmutable::class);
    expect($expense->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($expense->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to a transaction', function (): void {
    $expense = Expense::factory()->create()->fresh();

    expect($expense->transaction)->toBeInstanceOf(Transaction::class);
    expect($expense->transaction->id)->toBe($expense->transaction_id);
});

it('belongs to an expense type', function (): void {
    $expense = Expense::factory()->create()->fresh();

    expect($expense->expenseType)->toBeInstanceOf(ExpenseType::class);
    expect($expense->expenseType->id)->toBe($expense->expense_type_id);
});

it('can belong to a member', function (): void {
    $expense = Expense::factory()->create()->fresh();

    if ($expense->member_id) {
        expect($expense->member)->toBeInstanceOf(Member::class);
        expect($expense->member->id)->toBe($expense->member_id);
    } else {
        expect($expense->member)->toBeNull();
    }
});

it('can have no associated member', function (): void {
    $expense = Expense::factory()->create([
        'member_id' => null,
    ])->fresh();

    expect($expense->member_id)->toBeNull();
    expect($expense->member)->toBeNull();
});
