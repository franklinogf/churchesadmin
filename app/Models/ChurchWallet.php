<?php

declare(strict_types=1);

namespace App\Models;

use Bavix\Wallet\Interfaces\Confirmable;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\CanConfirm;
use Bavix\Wallet\Traits\HasWalletFloat;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $bank_name
 * @property string $bank_routing_number
 * @property string $bank_account_number
 * @property string|null $note
 * @property string|null $check_number
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property DateTimeInterface|null $deleted_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder<static>
 */
final class ChurchWallet extends Model implements WalletFloat, Confirmable, Wallet
{
    use CanConfirm, HasWalletFloat, SoftDeletes;
}
