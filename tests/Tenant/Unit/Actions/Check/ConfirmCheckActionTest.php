<?php

declare(strict_types=1);

use App\Actions\Check\ConfirmCheckAction;
use App\Exceptions\WalletException;
use App\Models\Check;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('confirms a check successfully', function (): void {
    // Create dependencies
    $wallet = ChurchWallet::factory()->create();
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    // Create a transaction - use the actual wallet instance
    $transaction = $wallet->depositFloat(
        '100.00',
        ['type' => 'check'],
        false // Not confirmed
    );

    // Create a check with the transaction
    $check = Check::create([
        'transaction_id' => $transaction->id,
        'member_id' => $member->id,
        'date' => now()->format('Y-m-d'),
        'type' => 'payment',
        'expense_type_id' => $expenseType->id,
    ]);

    $action = app(abstract: ConfirmCheckAction::class);

    // Execute the action
    $result = $action->handle($check);

    // Assert the result
    expect($result)->toBeTrue();

    // Reload the transaction
    $transaction->refresh();

    // Verify it was confirmed
    expect($transaction->confirmed)->toBeTrue();
});

test('ConfirmCheckAction handles failed confirmation for already confirmed transaction', function (): void {
    // Create dependencies
    $wallet = ChurchWallet::factory()->create();
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    // Create a transaction that's already confirmed
    $transaction = $wallet->depositFloat(
        '100.00',
        ['type' => 'check'],
        true // Already confirmed
    );

    // Create a check with the transaction
    $check = Check::create([
        'transaction_id' => $transaction->id,
        'member_id' => $member->id,
        'date' => now()->format('Y-m-d'),
        'type' => 'payment',
        'expense_type_id' => $expenseType->id,
    ]);

    // Create the real action instance
    $action = app(abstract: ConfirmCheckAction::class);

    // Expect an exception since the transaction is already confirmed
    expect(fn () => $action->handle($check))
        ->toThrow(WalletException::class);
});
