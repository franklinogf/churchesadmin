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
            self::SINGLE => __('enum.civil_status.single'),
            self::MARRIED => __('enum.civil_status.married'),
            self::DIVORCED => __('enum.civil_status.divorced'),
            self::WIDOWED => __('enum.civil_status.widowed'),
            self::SEPARATED => __('enum.civil_status.separated'),
        };
    }
}
