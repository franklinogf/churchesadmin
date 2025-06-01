<?php

declare(strict_types=1);

use App\Actions\Expense\DeleteExpenseAction;
use App\Models\Expense;
use Bavix\Wallet\Models\Transaction;

it('can delete an expense and its transaction', function (): void {
    $expense = Expense::factory()->create();

    // Verify expense and transaction exist
    expect(Expense::find($expense->id))->not->toBeNull()
        ->and(Transaction::find($expense->transaction_id))->not->toBeNull();

    $wallet = $expense->transaction->wallet->holder;

    $action = new DeleteExpenseAction();
    $action->handle($expense);

    // Verify expense is deleted
    expect(Expense::find($expense->id))->toBeNull()
        ->and(Transaction::find($expense->transaction_id))->toBeNull();

    // Verify wallet balance is refreshed (should be back to original 100.00)
    expect($wallet->fresh()->balanceFloat)->toBe('100.00');
});

it('deletes expense in a transaction to ensure consistency', function (): void {
    $expense = Expense::factory()->create();

    $expenseId = $expense->id;
    $transactionId = $expense->transaction_id;

    $action = new DeleteExpenseAction();
    $action->handle($expense);

    // Both expense and transaction should be deleted atomically
    expect(Expense::find($expenseId))->toBeNull()
        ->and(Transaction::find($transactionId))->toBeNull();
});
