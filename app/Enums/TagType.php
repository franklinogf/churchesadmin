<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum TagType: string
{
    use EnumToArray;
    case SKILL = 'skill';

    case CATEGORY = 'category';

    public function label(): string
    {
        return match ($this) {
            self::SKILL => __('Skill'),
            self::CATEGORY => __('Category'),
        };
    }
}
