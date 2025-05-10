<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class WalletException extends Exception
{
    public static function insufficientFunds(string $walletName): self
    {
        return new self(__('flash.message.wallet.insufficient_funds', [
            'wallet' => $walletName,
        ]), 422);
    }

    public static function emptyBalance(string $walletName): self
    {
        return new self(__('flash.message.wallet.empty_balance', [
            'wallet' => $walletName,
        ]), 422);
    }

    public static function notFound(): self
    {
        return new self(__('flash.message.wallet.not_found'), 422);
    }

    public static function invalidAmount(): self
    {
        return new self(__('flash.message.wallet.invalid_amount'), 422);
    }

    public static function transactionFailed(): self
    {
        return new self(__('flash.message.wallet.transaction_failed'), 422);
    }
}
