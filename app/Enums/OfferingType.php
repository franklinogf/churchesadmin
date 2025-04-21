<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum OfferingType: string
{
    use EnumToArray, HasOptions;
    case CASH = 'cash';
    case CHECK = 'check';
    case INITIAL = 'initial';

    /**
     * Get the options for the enum cases.
     *
     * @return array<mixed>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->filter(fn (self $case) => $case !== self::INITIAL)
            ->map(fn (self $case): array => [
                'value' => $case->value ?? $case->name,
                'label' => $case->label(),
            ])->toArray();
    }

    public function label(): string
    {
        return match ($this) {
            self::CASH => __('enum.offering_type.cash'),
            self::CHECK => __('enum.offering_type.check'),
            self::INITIAL => __('enum.offering_type.initial'),
        };
    }
}
