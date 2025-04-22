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
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::CASH => __('enum.payment_method.cash'),
            self::CHECK => __('enum.payment_method.check'),
        };
    }
}
