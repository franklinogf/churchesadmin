<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Traits;

use App\Enums\Traits\EnumToArray;

enum TestUnitEnum
{
    use EnumToArray;

    case ONE;
    case TWO;
    case THREE;
}
