<?php

declare(strict_types=1);

namespace App\Models;

use Bavix\Wallet\Models\Transfer;
use Bavix\Wallet\Models\Wallet as BaseWallet;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

final class Wallet extends BaseWallet
{
    use CentralConnection, HasTranslations;

    public $translatable = ['name', 'description'];

    public function canWithdraw(int|string $amount, bool $allowZero = false): bool
    {
        // Implement your logic here
        return true;
    }

    public function deposit(int|string $amount, ?array $meta = null, bool $confirmed = true): Transaction
    {
        // Implement your logic here
        return parent::deposit($amount, $meta, $confirmed);
    }

    public function depositFrom(int|string $amount, Model $payer, ?array $meta = null, bool $confirmed = true): Transaction
    {
        // Implement your logic here
        $transaction = parent::deposit($amount, $meta, $confirmed);

        $transaction->update([
            'payer_id' => $payer->getKey(),
            'payer_type' => $payer->getMorphClass(),
        ]);

        return $transaction;
    }

    public function forceTransfer(\Bavix\Wallet\Interfaces\Wallet $wallet, int|string $amount, array|\Bavix\Wallet\External\Contracts\ExtraDtoInterface|null $meta = null): Transfer
    {
        // Implement your logic here
        return parent::forceTransfer($wallet, $amount, $meta);
    }

    public function forceWithdraw(int|string $amount, ?array $meta = null, bool $confirmed = true): Transaction
    {
        // Implement your logic here
        return parent::forceWithdraw($amount, $meta, $confirmed);
    }

    public function getBalanceAttribute(): string
    {
        // Implement your logic here
        return parent::getBalanceAttribute();
    }

    public function safeTransfer(\Bavix\Wallet\Interfaces\Wallet $wallet, int|string $amount, array|\Bavix\Wallet\External\Contracts\ExtraDtoInterface|null $meta = null): ?Transfer
    {
        // Implement your logic here
        return parent::safeTransfer($wallet, $amount, $meta);
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        // Implement your logic here
        return parent::transactions();
    }

    public function transfer(\Bavix\Wallet\Interfaces\Wallet $wallet, int|string $amount, array|\Bavix\Wallet\External\Contracts\ExtraDtoInterface|null $meta = null): Transfer
    {
        // Implement your logic here
        return parent::transfer($wallet, $amount, $meta);
    }

    public function transfers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // Implement your logic here
        return parent::transfers();
    }

    public function withdraw(int|string $amount, ?array $meta = null, bool $confirmed = true): Transaction
    {
        // Implement your logic here
        return parent::withdraw($amount, $meta, $confirmed);
    }
}
