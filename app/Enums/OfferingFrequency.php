<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Interfaces\Labeable;
use App\Enums\Traits\EnumToArray;

enum OfferingFrequency: string implements Labeable
{
    use EnumToArray;
    case WEEKLY = 'weekly';
    case BIWEEKLY = 'biweekly';
    case MONTHLY = 'monthly';
    case BIMONTHLY = 'bimonthly';
    case QUARTERLY = 'quarterly';
    case SEMIANNUALLY = 'semiannually';
    case ANNUALLY = 'annually';
    case ONE_TIME = 'one_time';

    /**
     * Get the options for the enum.
     *
     * @return array
     */
    public static function options(): array
    {
        return [
            self::WEEKLY->value => self::WEEKLY->label(),
            self::BIWEEKLY->value => self::BIWEEKLY->label(),
            self::MONTHLY->value => self::MONTHLY->label(),
            self::BIMONTHLY->value => self::BIMONTHLY->label(),
            self::QUARTERLY->value => self::QUARTERLY->label(),
            self::SEMIANNUALLY->value => self::SEMIANNUALLY->label(),
            self::ANNUALLY->value => self::ANNUALLY->label(),
            self::ONE_TIME->value => self::ONE_TIME->label(),
        ];
    }

    /**
     * Get the label for the enum value.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::WEEKLY => 'Every week',
            self::BIWEEKLY => 'Every two weeks',
            self::MONTHLY => 'Every month',
            self::BIMONTHLY => 'Every two months',
            self::QUARTERLY => 'Every three months',
            self::SEMIANNUALLY => 'Every six months',
            self::ANNUALLY => 'Every year',
            self::ONE_TIME => 'One time only',
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
