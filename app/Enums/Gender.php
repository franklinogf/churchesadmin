<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Interfaces\Labeable;
use App\Enums\Traits\EnumToArray;

enum Gender: string implements Labeable
{
    use EnumToArray;
    case MALE = 'male';
    case FEMALE = 'female';

    /**
     * Get the label.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::MALE => __('Male'),
            self::FEMALE => __('Female'),
        };
    }
}
