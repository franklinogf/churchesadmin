<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LanguageCode: string implements HasColor, HasLabel
{
    use EnumToArray, HasOptions;

    case ENGLISH = 'en';
    case SPANISH = 'es';

    /**
     * Get the label for the language code.
     */
    public function label(): string
    {
        return __("enum.language_code.{$this->value}");
    }

    public function getLabel(): string
    {
        return $this->label();
    }

    /**
     * Get the color associated with the language code.
     *
     * @return array<int, string>
     */
    public function getColor(): array
    {
        return match ($this) {
            self::ENGLISH => Color::hex('#3B82F6'),
            self::SPANISH => Color::hex('#F87171'),
        };
    }
}
