<?php

declare(strict_types=1);

namespace App\Enums\Traits;

trait EnumToArray
{
    /**
     * Get the values of the enum cases.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the names of the enum cases.
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get the enum cases as an associative array.
     *
     * @return array<string, string>
     */
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
