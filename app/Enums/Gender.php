<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Interfaces\Labeable;
use App\Enums\Traits\EnumToArray;

enum Gender: string implements Labeable
{
    use EnumToArray;
    case MALE = 'male';
    case FEMALE = 'female';

    /**
     * Get the options.
     *
     * @return array<{value:string,label:string}>
     */
    public static function options(): array
    {
        return collect(self::cases())->map(fn (Gender $case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ])->toArray();
    }

    /**
     * Get the label.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::MALE => __('Male'),
            self::FEMALE => __('Female'),
        };
    }
}
