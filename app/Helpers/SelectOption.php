<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class SelectOption
{
    /**
     * Create an array of select options from a collection of items.
     *
     * @param  Collection<int,covariant Model>|null  $items
     * @param  string|array<int,string>  $labels
     * @return array<mixed>
     */
    public static function create(?Collection $items, string $value = 'id', string|array $labels = 'name', string $separator = ' '): array
    {
        if (! $items instanceof Collection) {
            return [];
        }

        return $items->map(fn ($item): array => [
            'value' => $item->{$value},
            'label' => is_array($labels)
            ? implode($separator, array_map(fn ($label) => $item->{$label}, $labels))
             : $item->{$labels},
        ])->toArray();
    }
}
