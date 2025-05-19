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
        return __("enum.language_code.{$this->value}");
    }

    public function getLabel(): string
    {
        return $this->label();
    }

    /**
     * Get the color associated with the language code.
     *
     * @return array{50:string, 100:string, 200:string, 300:string, 400:string, 500:string, 600:string, 700:string, 800:string, 900:string, 950:string}
     */
    public function getColor(): array
    {
        return match ($this) {
            self::ENGLISH => Color::hex('#3B82F6'),
            self::SPANISH => Color::hex('#F87171'),
        };
    }
}
