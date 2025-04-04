<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Interfaces\Labeable;
use App\Enums\Traits\EnumToArray;

enum CivilStatus: string implements Labeable
{
    use EnumToArray;
    case SINGLE = 'single';
    case MARRIED = 'married';
    case DIVORCED = 'divorced';
    case WIDOWED = 'widowed';
    case SEPARATED = 'separated';

    /**
     * Get the options for the civil status.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::SINGLE->value => self::SINGLE->label(),
            self::MARRIED->value => self::MARRIED->label(),
            self::DIVORCED->value => self::DIVORCED->label(),
            self::WIDOWED->value => self::WIDOWED->label(),
            self::SEPARATED->value => self::SEPARATED->label(),
        ];
    }

    /**
     * Get the label for the civil status.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::SINGLE => 'Single',
            self::MARRIED => 'Married',
            self::DIVORCED => 'Divorced',
            self::WIDOWED => 'Widowed',
            self::SEPARATED => 'Separated',
        };
    }
}
