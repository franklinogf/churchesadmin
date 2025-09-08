<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Models\ChurchWallet;

final class RestoreWalletAction
{
    public function handle(ChurchWallet $wallet): void
    {
        $wallet->restore();
    }
}
