<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum OfferingFrequency: string
{
    use EnumToArray, HasOptions;
    case ONE_TIME = 'one_time';
    case WEEKLY = 'weekly';
    case BIWEEKLY = 'biweekly';
    case MONTHLY = 'monthly';
    case BIMONTHLY = 'bimonthly';
    case QUARTERLY = 'quarterly';
    case SEMIANNUALLY = 'semiannually';
    case ANNUALLY = 'annually';

    /**
     * Get the label for the enum value.
     *
     * @return string
     */
    public function label(): string
    {
        return __("enum.offering_frequency.{$this->value}");
    }

    /**
     * Get the frequency in days.
     *
     * @return int
     */
    public function frequencyInDays(): int
    {
        return match ($this) {
            self::WEEKLY => 7,
            self::BIWEEKLY => 14,
            self::MONTHLY => 30,
            self::BIMONTHLY => 60,
            self::QUARTERLY => 90,
            self::SEMIANNUALLY => 180,
            self::ANNUALLY => 365,
            self::ONE_TIME => 0,
        };
    }
}
