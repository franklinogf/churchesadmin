<?php

declare(strict_types=1);

use App\Actions\Check\DeleteCheckAction;
use App\Enums\CheckType;
use App\Models\Check;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('deletes a check and its transaction', function (): void {
    // Create dependencies
    $wallet = ChurchWallet::factory()->create(); // Ensure zero initial balance
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    // Create a transaction
    $transaction = $wallet->depositFloat(
        '100.00',
        ['type' => 'check'],
        true // Confirmed to affect balance
    );

    // Create a check with the transaction
    $check = Check::create([
        'transaction_id' => $transaction->id,
        'member_id' => $member->id,
        'date' => now()->format('Y-m-d'),
        'type' => CheckType::PAYMENT->value,
        'expense_type_id' => $expenseType->id,
    ]);

    // Verify the balance was updated by the deposit (should be 100.00)
    $wallet->refresh();
    expect($wallet->balanceFloat)->toBe('100.00');

    // Create the action
    $action = new DeleteCheckAction();

    // Execute the action
    $action->handle($check);

    // Reload the wallet to get the updated balance
    $wallet->refresh();

    // Check that the check was deleted
    expect(Check::find($check->id))->toBeNull();

    // Check that the transaction was deleted
    $transactionExists = DB::table('transactions')->where('id', $transaction->id)->exists();
    expect($transactionExists)->toBeFalse();

    // Check that the wallet balance was refreshed (should be back to initial state of 0 after transaction deletion)
    expect($wallet->balanceFloat)->toBe('0.00');
});
