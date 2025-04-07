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
     * Get the options.
     *
     * @return array<{value:string,label:string}>
     */
    public static function options(): array
    {
        return collect(self::cases())->map(fn (CivilStatus $case) => [
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
            self::SINGLE => 'Single',
            self::MARRIED => 'Married',
            self::DIVORCED => 'Divorced',
            self::WIDOWED => 'Widowed',
            self::SEPARATED => 'Separated',
        };
    }
}
