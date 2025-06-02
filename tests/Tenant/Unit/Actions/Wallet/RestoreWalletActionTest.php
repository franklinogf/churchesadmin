<?php

declare(strict_types=1);

use App\Actions\Wallet\RestoreWalletAction;
use App\Models\ChurchWallet;

it('can restore a deleted wallet', function () {
    $wallet = ChurchWallet::factory()->create();
    $wallet->delete();

    $this->assertSoftDeleted('church_wallets', [
        'id' => $wallet->id,
    ]);

    $action = new RestoreWalletAction();
    $action->handle($wallet);

    $this->assertDatabaseHas('church_wallets', [
        'id' => $wallet->id,
        'deleted_at' => null,
    ]);
});
