<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;

final class SelectOption
{
    /**
     * Create an array of select options from a collection of items.
     *
     * @return array{value: string|int, label: string}[]
     */
    public static function create(Collection $items, string $value = 'id', string|array $labels = 'name', string $separator = ' '): array
    {
        return $items->map(fn ($item): array => [
            'value' => $item->{$value},
            'label' => is_array($labels)
            ? implode($separator, array_map(fn ($label) => $item->{$label}, $labels))
             : $item->{$labels},
        ])->toArray();
    }
}
