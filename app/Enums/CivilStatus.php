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
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::SINGLE => __('Single'),
            self::MARRIED => __('Married'),
            self::DIVORCED => __('Divorced'),
            self::WIDOWED => __('Widowed'),
            self::SEPARATED => __('Separated'),
        };
    }
}
