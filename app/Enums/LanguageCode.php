<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Interfaces\Labeable;
use App\Enums\Traits\EnumToArray;

enum LanguageCode: string implements Labeable
{
    use EnumToArray;
    case EN = 'en';
    case ES = 'es';

    /**
     * Get the options for the language code.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::EN->value => self::EN->label(),
            self::ES->value => self::ES->label(),
        ];
    }

    /**
     * Get the label for the language code.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::EN => 'English',
            self::ES => 'Spanish',
        };
    }
}
