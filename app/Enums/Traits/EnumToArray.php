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
}
