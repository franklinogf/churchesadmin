<?php

declare(strict_types=1);

use App\Actions\Expense\CreateExpenseAction;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Member;

it('can create an expense with member', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00');

    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    $expenseData = [
        'date' => '2024-01-01',
        'wallet_id' => $wallet->id,
        'member_id' => $member->id,
        'expense_type_id' => $expenseType->id,
        'amount' => '50.00',
        'note' => 'Test expense note',
    ];

    $action = app(CreateExpenseAction::class);
    $expense = $action->handle($expenseData);
    expect($expense)->toBeInstanceOf(Expense::class)
        ->and($expense->date->format('Y-m-d'))->toBe('2024-01-01')
        ->and($expense->member_id)->toBe($member->id)
        ->and($expense->expense_type_id)->toBe($expenseType->id)
        ->and($expense->note)->toBe('Test expense note')
        ->and($expense->transaction)->not->toBeNull()
        ->and($expense->transaction->amountFloat)->toBe('-50.00'); // Withdrawals are negative
    // Check wallet balance was reduced
    expect($wallet->fresh()->balanceFloat)->toBe('50.00');
});

it('can create an expense without member', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00');

    $expenseType = ExpenseType::factory()->create();

    $expenseData = [
        'date' => '2024-01-01',
        'wallet_id' => $wallet->id,
        'expense_type_id' => $expenseType->id,
        'amount' => '30.00',
    ];

    $action = app(CreateExpenseAction::class);
    $expense = $action->handle($expenseData);
    expect($expense)->toBeInstanceOf(Expense::class)
        ->and($expense->member_id)->toBeNull()
        ->and($expense->note)->toBeNull()
        ->and($expense->transaction->amountFloat)->toBe('-30.00'); // Withdrawals are negative
});

it('throws exception when wallet not found', function (): void {
    $expenseType = ExpenseType::factory()->create();

    $expenseData = [
        'date' => '2024-01-01',
        'wallet_id' => 'non-existent-id',
        'expense_type_id' => $expenseType->id,
        'amount' => '30.00',
    ];

    $action = app(CreateExpenseAction::class);

    expect(fn () => $action->handle($expenseData))
        ->toThrow(WalletException::class);
});

it('throws exception when insufficient wallet balance', function (): void {
    $wallet = ChurchWallet::factory()->create();
    // Don't add any balance to the wallet

    $expenseType = ExpenseType::factory()->create();

    $expenseData = [
        'date' => '2024-01-01',
        'wallet_id' => $wallet->id,
        'expense_type_id' => $expenseType->id,
        'amount' => '50.00',
    ];

    $action = app(CreateExpenseAction::class);

    expect(fn () => $action->handle($expenseData))
        ->toThrow(WalletException::class);
});
