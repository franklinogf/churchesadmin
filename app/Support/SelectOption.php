<?php

declare(strict_types=1);

namespace App\Support;

use BackedEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

use function is_array;

final class SelectOption
{
    /**
     * Create an array of select options from a collection of items.
     *
     * @param  Collection<int,covariant Model>|null  $items
     * @param  string|string[]  $labels
     * @return array<mixed>
     */
    public static function create(?Collection $items, string $value = 'id', string|array $labels = 'name', string $separator = ' '): array
    {
        if (! $items instanceof Collection) {
            return [];
        }

        return $items->map(fn (Model $item): array => [
            'value' => $item->{$value},
            'label' => is_array($labels)
            ? implode($separator, array_map(fn (string $label) => $item->{$label}, $labels))
             : $item->{$labels},
        ])->toArray();
    }

    /**
     * Create an array of select options from a collection of items for a multiple select.
     *
     * @param  Collection<int,covariant Model>|null  $items
     * @param  string|string[]  $labels
     * @return array<mixed>
     */
    public static function createForMultiple(string $heading, ?Collection $items, string $value = 'id', string|array $labels = 'name', string $separator = ' '): array
    {
        if (! $items instanceof Collection) {
            return [];
        }

        return [
            'heading' => $heading,
            'model' => $items->first()?->getMorphClass(),
            'options' => $items->map(fn (Model $item): array => [
                'value' => $item->{$value},
                'label' => is_array($labels)
                    ? implode($separator, array_map(fn (string $label) => $item->{$label}, $labels))
                    : $item->{$labels},
            ])->toArray(),
        ];

    }

    /**
     * Create an array of select options from an enum class.
     *
     * @param  class-string<BackedEnum|UnitEnum>  $enumClass
     * @return array{value: string, label: string}[]
     */
    public static function createFromEnum(string $enumClass): array
    {
        /**
         * @var array{value: string, label: string}[] $data
         */
        $data = collect($enumClass::cases())
            ->map(fn (BackedEnum|UnitEnum $case): array => [
                'value' => $case instanceof BackedEnum ? $case->value : $case->name,
                'label' => $case->name,
            ])
            ->toArray();

        return $data;
    }
}
