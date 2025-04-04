<?php

declare(strict_types=1);

namespace App\Enums\Traits;

trait EnumToArray
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');

    }

    public static function toArray(): array
    {
        if (self::values() === []) {
            return self::names();
        }

        if (self::names() === []) {
            return self::values();
        }

        return array_column(self::cases(), 'value', 'name');
    }
}
