<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Traits;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum TestBackedEnum: string
{
    use EnumToArray,HasOptions;

    case ADMIN = 'admin';
    case USER = 'user';
    case GUEST = 'guest';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'User',
            self::GUEST => 'Guest',
        };
    }
}
