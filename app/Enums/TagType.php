<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum TagType: string
{
    use EnumToArray, HasOptions;

    case SKILL = 'skill';

    case CATEGORY = 'category';

    public function label(): string
    {
        return __("enum.tag_type.{$this->value}");
    }
}
