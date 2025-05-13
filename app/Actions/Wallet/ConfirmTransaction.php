<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\ConfirmedInvalid;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Models\Transaction;

final class ConfirmTransaction
{
    /**
     * Handle the confirmation of a transaction.
     *
     * @return bool
     */
    public function handle(Transaction $transaction): bool
    {
        $churchWallet = ChurchWallet::find($transaction->wallet->holder_id);

        if (! $churchWallet instanceof ChurchWallet) {
            throw WalletException::notFound();
        }

        try {
            return $transaction->wallet->confirm($transaction);
        } catch (ConfirmedInvalid) {
            throw WalletException::alreadyConfirmed();
        } catch (BalanceIsEmpty) {
            throw WalletException::emptyBalance($churchWallet->name);
        } catch (InsufficientFunds) {
            throw WalletException::insufficientFunds($churchWallet->name);
        }
    }
}
