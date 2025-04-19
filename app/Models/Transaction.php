<?php

declare(strict_types=1);

namespace App\Models;

use App\Dtos\DepositMetaDto;
use Bavix\Wallet\Models\Transaction as BaseTransaction;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * @property-read int|null $payer_id
 * @property-read class-string<Member|Missionary>|null $payer_type
 * @property-read CarbonImmutable|null $deleted_at
 * @property-read DepositMetaDto|null $meta
 *
 * @mixin \Bavix\Wallet\Models\Transaction
 */
final class Transaction extends BaseTransaction
{
    use CentralConnection;

    protected function meta(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value !== null ? new DepositMetaDto(
                ...json_decode($value, true),
            ) : null,

        );
    }
}
