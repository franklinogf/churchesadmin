<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum OfferingFrequency: string
{
    use EnumToArray, HasOptions;
    case ONETIME = 'one_time';
    case WEEKLY = 'weekly';
    case BIWEEKLY = 'bi_weekly';
    case MONTHLY = 'monthly';
    case BIMONTHLY = 'bi_monthly';
    case QUARTERLY = 'quarterly';
    case SEMIANNUALLY = 'semi_annually';
    case ANNUALLY = 'annually';

    /**
     * Get the label for the enum value.
     */
    public function label(): string
    {
        return __("enum.offering_frequency.{$this->value}");
    }

    /**
     * Get the frequency in days.
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
            self::ONETIME => 0,
        };
    }
}
