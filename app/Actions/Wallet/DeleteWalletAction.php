<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Models\ChurchWallet;

final class DeleteWalletAction
{
    public function handle(ChurchWallet $wallet): void
    {
        $wallet->delete();
    }
}
