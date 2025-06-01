<?php

declare(strict_types=1);

use App\Actions\Expense\UpdateExpenseAction;
use App\Exceptions\WalletException;
use App\Models\Expense;
use App\Models\Member;

it('can update expense with new data', function (): void {

    $member = Member::factory()->create();
    $newMember = Member::factory()->create();

    $expense = Expense::factory()->create([
        'member_id' => $member->id,
    ]);

    $updateData = [
        'date' => '2024-01-15',
        'member_id' => $newMember->id,
        'amount' => '75.00',
        'note' => 'Updated expense note',
    ];

    $action = app(UpdateExpenseAction::class);
    $updatedExpense = $action->handle($expense, $updateData);

    expect($updatedExpense->date->format('Y-m-d'))->toBe('2024-01-15')
        ->and($updatedExpense->member_id)->toBe($newMember->id)
        ->and($updatedExpense->note)->toBe('Updated expense note')
        ->and($updatedExpense->transaction->amountFloat)->toBe('-75.00');
});

it('can update expense with partial data', function (): void {
    $member = Member::factory()->create();

    $expense = Expense::factory()->create([
        'member_id' => $member->id,
        'date' => '2024-01-01',
    ]);

    $updateData = [
        'note' => 'Only note updated',
    ];

    $action = app(UpdateExpenseAction::class);
    $updatedExpense = $action->handle($expense, $updateData);

    expect($updatedExpense->note)->toBe('Only note updated')
        ->and($updatedExpense->member_id)->toBe($member->id)
        ->and($updatedExpense->date->format('Y-m-d'))->toBe('2024-01-01');
});

it('can clear member_id and note with null values', function (): void {

    $member = Member::factory()->create();
    $expense = Expense::factory()->create([
        'member_id' => $member->id,
        'note' => 'Initial note',
    ]);

    $updateData = [
        'member_id' => null,
        'note' => null,
    ];

    $action = app(UpdateExpenseAction::class);
    $updatedExpense = $action->handle($expense, $updateData);

    expect($updatedExpense->member_id)->toBeNull()
        ->and($updatedExpense->note)->toBeNull();
});

it('throws exception when wallet not found', function (): void {
    $expense = Expense::factory()->create();
    $expense->transaction->wallet->delete(); // Ensure wallet is deleted

    $updateData = [
        'wallet_id' => 'non-existent-wallet-id',
        'amount' => '50.00',
    ];

    $action = app(UpdateExpenseAction::class);

    expect(fn () => $action->handle($expense, $updateData))
        ->toThrow(WalletException::class);
});
