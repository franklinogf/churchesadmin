<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum CivilStatus: string
{
    use EnumToArray, HasOptions;

    case SINGLE = 'single';
    case MARRIED = 'married';
    case DIVORCED = 'divorced';
    case WIDOWED = 'widowed';
    case SEPARATED = 'separated';

    /**
     * Get the label.
     */
    public function label(): string
    {
        return __("enum.civil_status.{$this->value}");
    }
}
