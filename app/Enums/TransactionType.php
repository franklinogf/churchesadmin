<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionType: string
{
    case INITIAL = 'initial';
    case CHECK = 'check';
    case OFFERING = 'offering';
    case EXPENSE = 'expense';
}
