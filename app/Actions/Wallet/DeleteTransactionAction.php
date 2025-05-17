<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\DB;

final class DeleteTransactionAction
{
    public function handle(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction): void {
            $wallet = $transaction->wallet;
            $transaction->forceDelete();
            $wallet->refreshBalance();
        });
    }
}
