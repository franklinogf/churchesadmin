<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionMetaType: string
{
    case INITIAL = 'initial';
    case CHECK = 'check';
    case OFFERING = 'offering';
    case EXPENSE = 'expense';

    public function label(): string
    {
        return __("enum.transaction_meta_type.{$this->value}");
    }
}
