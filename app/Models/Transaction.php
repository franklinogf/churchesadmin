<?php

declare(strict_types=1);

namespace App\Models;

use Bavix\Wallet\Models\Transaction as BaseTransaction;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * @property-read int|null $payer_id
 * @property-read class-string<Member|Missionary>|null $payer_type
 *
 * @mixin \Bavix\Wallet\Models\Transaction
 */
final class Transaction extends BaseTransaction
{
    use CentralConnection;

    public function payer(): MorphTo
    {
        return $this->morphTo();
    }
}
