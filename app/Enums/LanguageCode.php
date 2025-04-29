<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum LanguageCode: string
{
    use EnumToArray, HasOptions;
    case ENGLISH = 'en';
    case SPANISH = 'es';

    /**
     * Get the label for the language code.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ENGLISH => __('enum.language_code.en'),
            self::SPANISH => __('enum.language_code.es'),
        };
    }
}
