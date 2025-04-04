<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Interfaces\Labeable;
use App\Enums\Traits\EnumToArray;

enum AddressType: string implements Labeable
{
    use EnumToArray;

    case HOME = 'home';
    case WORK = 'work';
    case OTHER = 'other';

    /**
     * Get the options for the address type.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::HOME->value => self::HOME->label(),
            self::WORK->value => self::WORK->label(),
            self::OTHER->value => self::OTHER->label(),
        ];
    }

    /**
     * Get the label for the address type.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::HOME => 'Home',
            self::WORK => 'Work',
            self::OTHER => 'Other',
        };
    }
}
