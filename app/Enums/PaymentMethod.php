<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum PaymentMethod: string
{
    use EnumToArray, HasOptions;

    case CASH = 'cash';
    case CHECK = 'check';

    /**
     * Get the options for the enum cases.
     */
    public function label(): string
    {
        return __("enum.payment_method.{$this->value}");
    }
}
