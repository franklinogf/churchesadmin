<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class WalletException extends Exception
{
    public static function insufficientFunds(string $walletName): self
    {
        return new self(__('flash.message.insufficient_funds', [
            'wallet' => $walletName,
        ]));
    }

    public static function emptyBalance(string $walletName): self
    {
        return new self(__('flash.message.empty_balance', [
            'wallet' => $walletName,
        ]));
    }

    public static function notFound(): self
    {
        return new self(__('flash.message.wallet_not_found'));
    }
}
