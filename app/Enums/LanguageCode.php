<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LanguageCode: string implements HasLabel, HasColor
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

    public function getLabel(): string
    {
        return $this->label();
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ENGLISH => Color::hex('#3B82F6'),
            self::SPANISH => Color::hex('#F87171'),
        };
    }
}
