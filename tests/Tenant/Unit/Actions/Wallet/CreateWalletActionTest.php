<?php

declare(strict_types=1);

use App\Actions\Wallet\CreateWalletAction;
use App\Enums\TransactionMetaType;
use App\Models\ChurchWallet;

it('can create a wallet without balance', function () {
    $data = [
        'name' => 'Test Wallet',
        'description' => 'Test Description',
        'bank_name' => 'Test Bank',
        'bank_routing_number' => '123456789',
        'bank_account_number' => '987654321',
    ];

    $action = app(CreateWalletAction::class);
    $wallet = $action->handle($data);

    expect($wallet)->toBeInstanceOf(ChurchWallet::class);

    $this->assertDatabaseHas('church_wallets', [
        'name' => 'Test Wallet',
        'description' => 'Test Description',
        'bank_name' => 'Test Bank',
        'bank_routing_number' => '123456789',
        'bank_account_number' => '987654321',
        'slug' => 'test-wallet',
    ]);

    expect($wallet->balanceFloat)->toBe('0.00');
});

it('can create a wallet with initial balance', function () {
    $data = [
        'name' => 'Test Wallet with Balance',
        'description' => 'Test Description',
        'bank_name' => 'Test Bank',
        'bank_routing_number' => '123456789',
        'bank_account_number' => '987654321',
        'balance' => '100.50',
    ];

    $action = app(CreateWalletAction::class);
    $wallet = $action->handle($data);

    expect($wallet)->toBeInstanceOf(ChurchWallet::class);
    expect($wallet->balanceFloat)->toBe('100.50');

    $this->assertDatabaseHas('church_wallets', [
        'name' => 'Test Wallet with Balance',
        'slug' => 'test-wallet-with-balance',
    ]);

    // Check that initial transaction was created
    $initialTransaction = $wallet->initialTransaction;
    expect($initialTransaction)->not->toBeNull();
    expect($initialTransaction->meta['type'])->toBe(TransactionMetaType::INITIAL->value);
    expect($initialTransaction->amountFloat)->toBe('100.50');
});

it('handles null description correctly', function () {
    $data = [
        'name' => 'Test Wallet',
        'bank_name' => 'Test Bank',
        'bank_routing_number' => '123456789',
        'bank_account_number' => '987654321',
        'description' => null,
    ];

    $action = app(CreateWalletAction::class);
    $wallet = $action->handle($data);

    expect($wallet->description)->toBeNull();
});

it('handles zero balance correctly', function () {
    $data = [
        'name' => 'Test Wallet',
        'bank_name' => 'Test Bank',
        'bank_routing_number' => '123456789',
        'bank_account_number' => '987654321',
        'balance' => '0.00',
    ];

    $action = app(CreateWalletAction::class);
    $wallet = $action->handle($data);

    expect($wallet->balanceFloat)->toBe('0.00');

    // Should still create initial transaction for zero balance
    $initialTransaction = $wallet->initialTransaction;
    expect($initialTransaction)->not->toBeNull();
    expect($initialTransaction->amountFloat)->toBe('0.00');
});

it('generates correct slug from name', function () {
    $data = [
        'name' => 'My Special Wallet Name',
        'bank_name' => 'Test Bank',
        'bank_routing_number' => '123456789',
        'bank_account_number' => '987654321',
    ];

    $action = app(CreateWalletAction::class);
    $wallet = $action->handle($data);

    expect($wallet->slug)->toBe('my-special-wallet-name');
});
