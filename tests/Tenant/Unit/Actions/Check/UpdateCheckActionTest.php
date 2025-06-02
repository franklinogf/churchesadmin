<?php

declare(strict_types=1);

use App\Actions\Check\UpdateCheckAction;
use App\Enums\CheckType;
use App\Exceptions\WalletException;
use App\Models\Check;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('updates a check with all fields', function (): void {
    // Create dependencies with sufficient balance
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00'); // Ensure wallet has enough balance
    $newWallet = ChurchWallet::factory()->create();
    $newWallet->depositFloat('200.00'); // Ensure new wallet has enough balance
    $member = Member::factory()->create();
    $newMember = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();
    $newExpenseType = ExpenseType::factory()->create();

    // Create a transaction for the check - using withdrawFloat not depositFloat
    // since checks are withdrawals from a wallet (negative transaction)
    $transaction = $wallet->withdrawFloat(
        '100.00',
        ['type' => 'check'],
        false // Not confirmed
    );

    // Create a check
    $check = Check::create([
        'transaction_id' => $transaction->id,
        'member_id' => $member->id,
        'date' => '2023-01-01',
        'type' => CheckType::PAYMENT->value,
        'expense_type_id' => $expenseType->id,
        'check_number' => '12345',
        'note' => 'Original note',
    ]);

    // Create update data
    $updateData = [
        'amount' => '200.00', // Increasing amount
        'member_id' => $newMember->id,
        'date' => '2023-02-02',
        'type' => CheckType::REFUND->value,
        'wallet_id' => $newWallet->id, // Change wallet
        'expense_type_id' => $newExpenseType->id,
        'check_number' => '67890',
        'note' => 'Updated note',
        'confirmed' => true, // Confirm the check
    ];

    // Create the necessary actions
    $action = app(UpdateCheckAction::class);

    // Execute the update
    $updatedCheck = $action->handle($check, $updateData);

    // Reload the check
    $updatedCheck->refresh();

    // Assert the check was updated
    expect($updatedCheck->member_id)->toBe($newMember->id);
    expect($updatedCheck->date->format('Y-m-d'))->toBe('2023-02-02');
    expect($updatedCheck->type->value)->toBe(CheckType::REFUND->value);
    expect($updatedCheck->expense_type_id)->toBe($newExpenseType->id);
    expect($updatedCheck->check_number)->toBe('67890');
    expect($updatedCheck->note)->toBe('Updated note');

    // Check if the transaction was updated
    $updatedTransaction = $updatedCheck->transaction;
    expect($updatedTransaction->amountFloat)->toBe('-200.00'); // Negative for withdrawals
    expect($updatedTransaction->confirmed)->toBeTrue();

    // Check that the transaction is now connected to the new wallet
    $newWallet->refresh();
    expect($updatedTransaction->wallet->holder_id)->toBe($newWallet->id);
});

test('updates a check with minimal fields', function (): void {
    // Create dependencies with sufficient balance
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00'); // Ensure wallet has enough balance
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    // Create a transaction for the check - using withdrawFloat for check transactions
    $transaction = $wallet->withdrawFloat(
        '100.00',
        ['type' => 'check'],
        false // Not confirmed
    );

    // Create a check
    $check = Check::create([
        'transaction_id' => $transaction->id,
        'member_id' => $member->id,
        'date' => '2023-01-01',
        'type' => CheckType::PAYMENT->value,
        'expense_type_id' => $expenseType->id,
        'check_number' => '12345',
        'note' => 'Original note',
    ]);

    // Only update the note - must provide wallet_id in minimal update
    $updateData = [
        'note' => 'Updated note only',
    ];

    // Create the necessary actions
    $action = app(UpdateCheckAction::class);

    // Execute the update
    $updatedCheck = $action->handle($check, $updateData);

    // Reload the check
    $updatedCheck->refresh();

    // Assert only the note was updated
    expect($updatedCheck->member_id)->toBe($member->id); // Unchanged
    expect($updatedCheck->date->format('Y-m-d'))->toBe('2023-01-01'); // Unchanged
    expect($updatedCheck->type->value)->toBe(CheckType::PAYMENT->value); // Unchanged
    expect($updatedCheck->expense_type_id)->toBe($expenseType->id); // Unchanged
    expect($updatedCheck->check_number)->toBe('12345'); // Unchanged
    expect($updatedCheck->note)->toBe('Updated note only'); // Changed

    // Check transaction remains mostly unchanged
    $updatedTransaction = $updatedCheck->transaction;
    expect($updatedTransaction->amountFloat)->toBe('-100.00'); // Unchanged, negative for withdrawal
    expect($updatedTransaction->confirmed)->toBeFalse(); // Unchanged
    expect($updatedTransaction->wallet->holder_id)->toBe($wallet->id); // Unchanged
});

test('throws exception when wallet is not found', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00');
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    $transaction = $wallet->withdrawFloat(
        '100.00',
        ['type' => 'check'],
        false // Not confirmed
    );

    // Create a check
    $check = Check::create([
        'transaction_id' => $transaction->id,
        'member_id' => $member->id,
        'date' => '2023-01-01',
        'type' => CheckType::PAYMENT->value,
        'expense_type_id' => $expenseType->id,
        'check_number' => '12345',
        'note' => 'Original note',
    ]);

    $transaction->wallet->delete(); // Delete the wallet to simulate non-existence

    // Prepare check data with non-existent wallet ID
    $checkData = [
        'amount' => '50.00',
        'member_id' => $member->id,
        'date' => '2023-01-01',
        'type' => CheckType::PAYMENT->value,
        'wallet_id' => '999999',
        'expense_type_id' => $expenseType->id,
    ];

    // Create the action
    $action = app(UpdateCheckAction::class);

    // Execute and expect exception
    expect(fn () => $action->handle($check, $checkData))
        ->toThrow(WalletException::class);
});
