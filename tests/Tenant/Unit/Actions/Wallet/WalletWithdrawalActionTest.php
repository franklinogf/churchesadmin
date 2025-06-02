<?php

declare(strict_types=1);

use App\Actions\Wallet\WalletWithdrawalAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\ModelMorphName;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use Bavix\Wallet\Models\Transaction;

it('can create a withdrawal transaction', function (): void {
    $wallet = ChurchWallet::factory()->create();

    // First add money to the wallet
    $wallet->depositFloat('200.00', ['type' => TransactionMetaType::INITIAL->value], true);

    $transactionDto = new TransactionDto(
        amount: '75.50',
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    );

    $action = new WalletWithdrawalAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction)->toBeInstanceOf(Transaction::class);
    expect($transaction->amountFloat)->toBe('-75.50'); // Withdrawals are negative
    expect($transaction->meta['type'])->toBe(TransactionMetaType::EXPENSE->value);
    expect($transaction->confirmed)->toBeTrue();
    expect($wallet->balanceFloat)->toBe('124.50'); // 200 - 75.50
});

it('can create unconfirmed withdrawal transaction', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00', ['type' => TransactionMetaType::INITIAL->value], true);

    $transactionDto = new TransactionDto(
        amount: '50.00',
        meta: new TransactionMetaDto(TransactionMetaType::CHECK),
        confirmed: false
    );

    $action = new WalletWithdrawalAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction->amountFloat)->toBe('-50.00');
    expect($transaction->confirmed)->toBeFalse();

    // Unconfirmed transactions don't affect balance
    expect($wallet->balanceFloat)->toBe('100.00');
});

it('can handle multiple withdrawals', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('300.00', ['type' => TransactionMetaType::INITIAL->value], true);

    $action = new WalletWithdrawalAction();

    $transaction1 = $action->handle($wallet, new TransactionDto(
        amount: '100.00',
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    ));

    $transaction2 = $action->handle($wallet, new TransactionDto(
        amount: '75.25',
        meta: new TransactionMetaDto(TransactionMetaType::CHECK),
        confirmed: true
    ));

    expect($transaction1->amountFloat)->toBe('-100.00');
    expect($transaction2->amountFloat)->toBe('-75.25');
    expect($wallet->balanceFloat)->toBe('124.75'); // 300 - 100 - 75.25
});

it('throws exception for insufficient funds', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('50.00', ['type' => TransactionMetaType::INITIAL->value], true);

    $transactionDto = new TransactionDto(
        amount: '100.00', // More than available balance
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    );

    $action = new WalletWithdrawalAction();

    expect(fn (): Transaction => $action->handle($wallet, $transactionDto))
        ->toThrow(WalletException::class);
});

it('throws exception for empty balance withdrawal', function (): void {
    $wallet = ChurchWallet::factory()->create();
    // Wallet has 0 balance

    $transactionDto = new TransactionDto(
        amount: '10.00',
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    );

    $action = new WalletWithdrawalAction();

    expect(fn (): Transaction => $action->handle($wallet, $transactionDto))
        ->toThrow(WalletException::class);
});

it('throws exception for invalid amount', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00', ['type' => TransactionMetaType::INITIAL->value], true);

    $transactionDto = new TransactionDto(
        amount: '-25.00', // Invalid negative amount
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    );

    $action = new WalletWithdrawalAction();

    expect(fn (): Transaction => $action->handle($wallet, $transactionDto))
        ->toThrow(WalletException::class);
});

it('can handle exact balance withdrawal', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00', ['type' => TransactionMetaType::INITIAL->value], true);

    $transactionDto = new TransactionDto(
        amount: '100.00', // Exact balance
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    );

    $action = new WalletWithdrawalAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction->amountFloat)->toBe('-100.00');
    expect($wallet->balanceFloat)->toBe('0.00');
});

it('handles different transaction meta types', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('500.00', ['type' => TransactionMetaType::INITIAL->value], true);

    $action = new WalletWithdrawalAction();

    $expenseTransaction = $action->handle($wallet, new TransactionDto(
        amount: '100.00',
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    ));

    $checkTransaction = $action->handle($wallet, new TransactionDto(
        amount: '200.00',
        meta: new TransactionMetaDto(TransactionMetaType::CHECK),
        confirmed: true
    ));

    expect($expenseTransaction->meta['type'])->toBe(TransactionMetaType::EXPENSE->value);
    expect($checkTransaction->meta['type'])->toBe(TransactionMetaType::CHECK->value);
    expect($wallet->balanceFloat)->toBe('200.00'); // 500 - 100 - 200
});

it('handles decimal amounts correctly', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('150.75', ['type' => TransactionMetaType::INITIAL->value], true);

    $transactionDto = new TransactionDto(
        amount: '25.25',
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    );

    $action = new WalletWithdrawalAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction->amountFloat)->toBe('-25.25');
    expect($wallet->balanceFloat)->toBe('125.50'); // 150.75 - 25.25
});

it('creates transaction with correct wallet relationship', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('100.00', ['type' => TransactionMetaType::INITIAL->value], true);

    $transactionDto = new TransactionDto(
        amount: '50.00',
        meta: new TransactionMetaDto(TransactionMetaType::EXPENSE),
        confirmed: true
    );

    $action = new WalletWithdrawalAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction->wallet->holder_id)->toBe($wallet->id);
    expect($transaction->wallet->holder_type)->toBe(ModelMorphName::CHURCH_WALLET->value);
});
