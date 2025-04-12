<?php

declare(strict_types=1);

namespace App\Enums\Traits;

trait HasOptions
{
    abstract public function label(): string;

    /**
     * Get the options for the enum cases.
     *
     * @return array<mixed>
     */
    public static function options(): array
    {

        return collect(self::cases())->map(fn (self $case): array => [
            'value' => $case->value,
            'label' => $case->label(),
        ])->toArray();
    }

    /**
     * Get the option object for the enum.
     *
     * @return array{value:mixed,label:string}
     */
    public function asOption(): array
    {
        /**
         * @return array{value:string|int,label:string}
         */
        return [
            'value' => $this->value,
            'label' => $this->label(),
        ];
    }
}
