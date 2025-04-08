<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum LanguageCode: string
{
    use EnumToArray, HasOptions;
    case EN = 'en';
    case ES = 'es';

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
