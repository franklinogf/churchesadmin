<?php

declare(strict_types=1);

use App\Actions\Wallet\DeleteWalletAction;
use App\Models\ChurchWallet;

it('can delete a wallet', function (): void {
    $wallet = ChurchWallet::factory()->create();

    $action = new DeleteWalletAction();
    $action->handle($wallet);

    $this->assertSoftDeleted('church_wallets', [
        'id' => $wallet->id,
    ]);
});

it('can delete wallet with transactions', function (): void {
    $wallet = ChurchWallet::factory()->create();

    // Add some transactions to the wallet
    $wallet->depositFloat('100.00', ['type' => 'test'], true);
    $wallet->withdrawFloat('50.00', ['type' => 'test'], true);

    expect($wallet->walletTransactions()->get())->toHaveCount(2);

    $action = new DeleteWalletAction();
    $action->handle($wallet);

    $this->assertSoftDeleted('church_wallets', [
        'id' => $wallet->id,
    ]);

    // Transactions should still exist (not cascade deleted)
    expect($wallet->walletTransactions()->get())->toHaveCount(2);
});
