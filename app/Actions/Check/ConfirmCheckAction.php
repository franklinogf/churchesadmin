<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Actions\Wallet\ConfirmTransaction;
use App\Exceptions\WalletException;
use App\Models\Check;
use App\Models\ChurchWallet;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\ConfirmedInvalid;
use Bavix\Wallet\Exceptions\InsufficientFunds;

final class ConfirmCheckAction
{
    public function __construct(
        private readonly ConfirmTransaction $confirmTransaction,
    ) {}

    public function handle(Check $check): bool
    {

        $churchWallet = ChurchWallet::find($check->transaction->wallet->holder_id);

        if (! $churchWallet instanceof ChurchWallet) {
            throw WalletException::notFound();
        }

        try {
            return $check->transaction->wallet->confirm($check->transaction);
        } catch (ConfirmedInvalid) {
            throw WalletException::alreadyConfirmed();
        } catch (BalanceIsEmpty) {
            throw WalletException::emptyBalance($churchWallet->name);
        } catch (InsufficientFunds) {
            throw WalletException::insufficientFunds($churchWallet->name);
        }

    }
}
