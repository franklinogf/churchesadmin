<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum Gender: string
{
    use EnumToArray, HasOptions;
    case MALE = 'male';
    case FEMALE = 'female';

    /**
     * Get the label.
     */
    public function label(): string
    {
        return __("enum.gender.{$this->value}");
    }
}
