<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Traits;

use App\Enums\Traits\EnumToArray;

enum TestBackedEnum: string
{
    use EnumToArray;

    case ADMIN = 'admin';
    case USER = 'user';
    case GUEST = 'guest';
}
