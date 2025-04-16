<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Traits;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum TestUnitEnum
{
    use EnumToArray, HasOptions;

    case ONE;
    case TWO;
    case THREE;

    public function label(): string
    {
        return match ($this) {
            self::ONE => 'One',
            self::TWO => 'Two',
            self::THREE => 'Three',
        };
    }
}
