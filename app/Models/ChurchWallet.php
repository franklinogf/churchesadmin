<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionMetaType;
use Bavix\Wallet\Interfaces\Confirmable;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Traits\CanConfirm;
use Bavix\Wallet\Traits\HasWalletFloat;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read string $description
 * @property-read string $bank_name
 * @property-read string $bank_routing_number
 * @property-read string $bank_account_number
 * @property-read string|null $note
 * @property-read string|null $check_number
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 * @property-read DateTimeInterface|null $deleted_at
 * @property-read int|null $check_layout_id
 * @property-read CheckLayout|null $checkLayout
 * @property-read Transaction|null $initialTransaction
 */
final class ChurchWallet extends Model implements WalletFloat, Confirmable, Wallet
{
    use CanConfirm, HasWalletFloat, SoftDeletes;

    /**
     * The layout that the check is using.
     *
     * @return BelongsTo<CheckLayout, $this>
     */
    public function checkLayout(): BelongsTo
    {
        return $this->belongsTo(CheckLayout::class);
    }

    /**
     * The transactions that belong to the wallet.
     *
     * @return MorphOne<Transaction, Model>
     */
    public function initialTransaction(): MorphOne
    {
        return $this->transactions()
            ->one()
            ->withAttributes(['meta->type' => TransactionMetaType::INITIAL->value]);
    }
}
