<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';

    public function label(): string
    {
        return __("enum.transaction_type.{$this->value}");
    }
}
