<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum OfferingFrequency: string
{
    use EnumToArray, HasOptions;
    case WEEKLY = 'weekly';
    case BIWEEKLY = 'biweekly';
    case MONTHLY = 'monthly';
    case BIMONTHLY = 'bimonthly';
    case QUARTERLY = 'quarterly';
    case SEMIANNUALLY = 'semiannually';
    case ANNUALLY = 'annually';
    case ONE_TIME = 'one_time';

    /**
     * Get the label for the enum value.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::WEEKLY => __('Every week'),
            self::BIWEEKLY => __('Every two weeks'),
            self::MONTHLY => __('Every month'),
            self::BIMONTHLY => __('Every two months'),
            self::QUARTERLY => __('Every three months'),
            self::SEMIANNUALLY => __('Every six months'),
            self::ANNUALLY => __('Every year'),
            self::ONE_TIME => __('One time only'),
        };
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
