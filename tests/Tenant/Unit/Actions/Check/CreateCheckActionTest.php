<?php

declare(strict_types=1);

use App\Actions\Check\CreateCheckAction;
use App\Enums\CheckType;
use App\Exceptions\WalletException;
use App\Models\Check;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('creates a check with all fields', function () {
    $wallet = ChurchWallet::factory()->create();
    // Ensure the wallet has enough balance
    $wallet->depositFloat('100.00');
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    // Prepare the check data
    $checkData = [
        'amount' => '50.00',
        'member_id' => $member->id,
        'date' => '2023-01-01',
        'type' => CheckType::PAYMENT->value,
        'wallet_id' => $wallet->id,
        'expense_type_id' => $expenseType->id,
        'check_number' => '12345',
        'note' => 'Test note',
    ];

    // Create the action

    $action = app(CreateCheckAction::class);

    // Execute the action
    $check = $action->handle($checkData);

    // Assertions
    expect($check)->toBeInstanceOf(Check::class)
        ->and($check->member_id)->toBe($member->id)
        ->and($check->date->format('Y-m-d'))->toBe('2023-01-01')
        ->and($check->type->value)->toBe(CheckType::PAYMENT->value)
        ->and($check->expense_type_id)->toBe($expenseType->id)
        ->and($check->check_number)->toBe('12345')
        ->and($check->note)->toBe('Test note');

    // Check that the transaction was created correctly
    expect($check->transaction)->not->toBeNull()
        ->and($check->transaction->amountFloat)->toBe('-50.00')
        ->and($check->transaction->confirmed)->toBeFalse();

});

test('creates a check with minimal fields', function () {

    $wallet = ChurchWallet::factory()->create();
    // Ensure the wallet has enough balance
    $wallet->depositFloat('100.00');
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    // Prepare minimal check data (without optional fields)
    $checkData = [
        'amount' => '25.00',
        'member_id' => $member->id,
        'date' => '2023-01-01',
        'type' => CheckType::PAYMENT->value,
        'wallet_id' => $wallet->id,
        'expense_type_id' => $expenseType->id,
    ];

    // Create the action
    $action = app(CreateCheckAction::class);

    // Execute the action
    $check = $action->handle($checkData);

    // Assertions
    expect($check)->toBeInstanceOf(Check::class)
        ->and($check->member_id)->toBe($member->id)
        ->and($check->date->format('Y-m-d'))->toBe('2023-01-01')
        ->and($check->type->value)->toBe(CheckType::PAYMENT->value)
        ->and($check->expense_type_id)->toBe($expenseType->id)
        ->and($check->check_number)->toBeNull()
        ->and($check->note)->toBeNull();

    // Check that the transaction was created
    expect($check->transaction)->not->toBeNull()
        ->and($check->transaction->amountFloat)->toBe('-25.00');

});

test('throws exception when wallet balance is insufficient', function () {
    $wallet = ChurchWallet::factory()->create();
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

    // Prepare check data
    $checkData = [
        'amount' => '50.00', // More than the wallet balance
        'member_id' => $member->id,
        'date' => '2023-01-01',
        'type' => CheckType::PAYMENT->value,
        'wallet_id' => $wallet->id,
        'expense_type_id' => $expenseType->id,
    ];

    // Create the action
    $action = app(CreateCheckAction::class);

    // Execute and expect exception
    expect(fn () => $action->handle($checkData))
        ->toThrow(WalletException::class);
});

test('throws exception when wallet is not found', function () {
    // Create dependencies but don't create the wallet
    $member = Member::factory()->create();
    $expenseType = ExpenseType::factory()->create();

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
    $action = app(CreateCheckAction::class);

    // Execute and expect exception
    expect(fn () => $action->handle($checkData))
        ->toThrow(WalletException::class);
});
