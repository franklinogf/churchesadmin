<?php

declare(strict_types=1);

use App\Actions\Wallet\WalletDepositAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\ModelMorphName;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use Bavix\Wallet\Models\Transaction;

it('can create a deposit transaction', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $transactionDto = new TransactionDto(
        amount: '100.50',
        meta: new TransactionMetaDto(TransactionMetaType::OFFERING),
        confirmed: true
    );

    $action = new WalletDepositAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction)->toBeInstanceOf(Transaction::class);
    expect($transaction->amountFloat)->toBe('100.50');
    expect($transaction->meta['type'])->toBe(TransactionMetaType::OFFERING->value);
    expect($transaction->confirmed)->toBeTrue();
    expect($wallet->balanceFloat)->toBe('100.50');
});

it('can create unconfirmed deposit transaction', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $transactionDto = new TransactionDto(
        amount: '75.25',
        meta: new TransactionMetaDto(TransactionMetaType::CHECK),
        confirmed: false
    );

    $action = new WalletDepositAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction->amountFloat)->toBe('75.25');
    expect($transaction->confirmed)->toBeFalse();

    // Unconfirmed transactions don't affect balance
    expect($wallet->balanceFloat)->toBe('0.00');
});

it('can handle multiple deposits', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $action = new WalletDepositAction();

    $transaction1 = $action->handle($wallet, new TransactionDto(
        amount: '50.00',
        meta: new TransactionMetaDto(TransactionMetaType::OFFERING),
        confirmed: true
    ));

    $transaction2 = $action->handle($wallet, new TransactionDto(
        amount: '25.75',
        meta: new TransactionMetaDto(TransactionMetaType::INITIAL),
        confirmed: true
    ));

    expect($transaction1->amountFloat)->toBe('50.00');
    expect($transaction2->amountFloat)->toBe('25.75');
    expect($wallet->balanceFloat)->toBe('75.75');
});

it('can handle different transaction meta types', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $action = new WalletDepositAction();

    $offeringTransaction = $action->handle($wallet, new TransactionDto(
        amount: '100.00',
        meta: new TransactionMetaDto(TransactionMetaType::OFFERING),
        confirmed: true
    ));

    $initialTransaction = $action->handle($wallet, new TransactionDto(
        amount: '200.00',
        meta: new TransactionMetaDto(TransactionMetaType::INITIAL),
        confirmed: true
    ));

    expect($offeringTransaction->meta['type'])->toBe(TransactionMetaType::OFFERING->value);
    expect($initialTransaction->meta['type'])->toBe(TransactionMetaType::INITIAL->value);
    expect($wallet->balanceFloat)->toBe('300.00');
});

it('throws exception for invalid amount', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $transactionDto = new TransactionDto(
        amount: '-50.00', // Invalid negative amount
        meta: new TransactionMetaDto(TransactionMetaType::OFFERING),
        confirmed: true
    );

    $action = new WalletDepositAction();

    expect(fn (): Transaction => $action->handle($wallet, $transactionDto))
        ->toThrow(WalletException::class);
});

it('handles zero amount deposits', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $transactionDto = new TransactionDto(
        amount: '0.00',
        meta: new TransactionMetaDto(TransactionMetaType::INITIAL),
        confirmed: true
    );

    $action = new WalletDepositAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction->amountFloat)->toBe('0.00');
    expect($wallet->balanceFloat)->toBe('0.00');
});

it('handles decimal amounts correctly', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $transactionDto = new TransactionDto(
        amount: '123.45',
        meta: new TransactionMetaDto(TransactionMetaType::OFFERING),
        confirmed: true
    );

    $action = new WalletDepositAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction->amountFloat)->toBe('123.45');
    expect($wallet->balanceFloat)->toBe('123.45');
});

it('creates transaction with correct wallet relationship', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $transactionDto = new TransactionDto(
        amount: '50.00',
        meta: new TransactionMetaDto(TransactionMetaType::OFFERING),
        confirmed: true
    );

    $action = new WalletDepositAction();
    $transaction = $action->handle($wallet, $transactionDto);

    expect($transaction->wallet->holder_id)->toBe($wallet->id);
    expect($transaction->wallet->holder_type)->toBe(ModelMorphName::CHURCH_WALLET->value);
});
