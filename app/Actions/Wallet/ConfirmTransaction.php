<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\ConfirmedInvalid;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Wallet
 */
final class ConfirmTransaction
{
    /**
     * @param  TModel  $model
     * @return bool
     */
    public function handle(Model $model): bool
    {
        $churchWallet = ChurchWallet::find($model->transaction->wallet->holder_id);

        if (! $churchWallet instanceof ChurchWallet) {
            throw WalletException::notFound();
        }

        try {
            return $model->transaction->wallet->confirm($model->transaction);
        } catch (ConfirmedInvalid) {
            throw WalletException::alreadyConfirmed();
        } catch (BalanceIsEmpty) {
            throw WalletException::emptyBalance($churchWallet->name);
        } catch (InsufficientFunds) {
            throw WalletException::insufficientFunds($churchWallet->name);
        }
    }
}
