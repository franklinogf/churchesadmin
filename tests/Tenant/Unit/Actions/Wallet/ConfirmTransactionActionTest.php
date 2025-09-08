<?php

declare(strict_types=1);

use App\Actions\Wallet\ConfirmTransactionAction;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;

it('can confirm an unconfirmed deposit transaction', function (): void {
    $wallet = ChurchWallet::factory()->create();

    // Create an unconfirmed deposit
    $transaction = $wallet->depositFloat('100.00', ['type' => TransactionMetaType::OFFERING->value], false);

    expect($transaction->confirmed)->toBeFalse();
    expect($wallet->balanceFloat)->toBe('0.00'); // Unconfirmed doesn't affect balance

    $action = new ConfirmTransactionAction();
    $result = $action->handle($transaction);

    expect($result)->toBeTrue();

    $transaction->refresh();
    expect($transaction->confirmed)->toBeTrue();
    expect($wallet->balanceFloat)->toBe('100.00'); // Now affects balance
});

it('can confirm an unconfirmed withdrawal transaction', function (): void {
    $wallet = ChurchWallet::factory()->create();

    // First add confirmed money
    $wallet->depositFloat('200.00', ['type' => TransactionMetaType::INITIAL->value], true);

    // Create an unconfirmed withdrawal
    $transaction = $wallet->withdrawFloat('75.50', ['type' => TransactionMetaType::EXPENSE->value], false);

    expect($transaction->confirmed)->toBeFalse();
    expect($wallet->balanceFloat)->toBe('200.00'); // Unconfirmed doesn't affect balance

    $action = new ConfirmTransactionAction();
    $result = $action->handle($transaction);

    expect($result)->toBeTrue();

    $transaction->refresh();
    expect($transaction->confirmed)->toBeTrue();
    expect($wallet->balanceFloat)->toBe('124.50'); // 200 - 75.50
});

it('throws exception when confirming already confirmed transaction', function (): void {
    $wallet = ChurchWallet::factory()->create();

    // Create a confirmed transaction
    $transaction = $wallet->depositFloat('100.00', ['type' => TransactionMetaType::OFFERING->value], true);

    expect($transaction->confirmed)->toBeTrue();

    $action = new ConfirmTransactionAction();

    expect(fn (): bool => $action->handle($transaction))
        ->toThrow(WalletException::class);
});

it('throws exception when wallet not found', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $transaction = $wallet->depositFloat('100.00', ['type' => TransactionMetaType::OFFERING->value], false);

    // Delete the wallet to simulate not found scenario
    $wallet->forceDelete();

    $action = new ConfirmTransactionAction();

    expect(fn (): bool => $action->handle($transaction))
        ->toThrow(WalletException::class);
});

it('throws exception for insufficient funds when confirming withdrawal', function (): void {
    $wallet = ChurchWallet::factory()->withBalance()->create();

    // Create unconfirmed withdrawal for more than available
    $transaction = $wallet->withdrawFloat('100.00', ['type' => TransactionMetaType::CHECK->value], false);

    $wallet->withdrawFloat('50.00', ['type' => TransactionMetaType::EXPENSE->value]);

    expect($wallet->balanceFloat)->toBe('50.00');

    $action = new ConfirmTransactionAction();

    expect($action->handle($transaction));

})->throws(WalletException::class);

it('throws exception for empty balance when confirming withdrawal', function (): void {
    $wallet = ChurchWallet::factory()->withBalance('30.00')->create();

    // Create unconfirmed withdrawal on empty wallet
    $transaction = $wallet->withdrawFloat('25.00', ['type' => TransactionMetaType::CHECK->value], false);

    $wallet->withdrawFloat('30.00', ['type' => TransactionMetaType::EXPENSE->value]);

    expect($wallet->balanceFloat)->toBe('0.00');

    $action = new ConfirmTransactionAction();

    $action->handle($transaction);
})->throws(WalletException::class);
