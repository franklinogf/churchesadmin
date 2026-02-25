<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum CheckType: string
{
    use EnumToArray, HasOptions;

    case PAYMENT = 'payment';
    case REFUND = 'refund';

    public function label(): string
    {
        return __("enum.check_type.{$this->value}");
    }
}
