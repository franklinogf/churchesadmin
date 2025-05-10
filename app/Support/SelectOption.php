<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

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
            'model' => Relation::getMorphAlias($items->getQueueableClass()),
            'options' => $items->map(fn (Model $item): array => [
                'value' => $item->{$value},
                'label' => is_array($labels)
                    ? implode($separator, array_map(fn (string $label) => $item->{$label}, $labels))
                    : $item->{$labels},
            ])->toArray(),
        ];

    }
}
