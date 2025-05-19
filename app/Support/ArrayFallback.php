<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Arr;

final class ArrayFallback
{
    /**
     * Get the value from the array or return the fallback value.
     *
     * @param  array<string,mixed>  $data
     * @return mixed
     */
    public static function inputOrFallback(array $data, string $key, mixed $fallback): mixed
    {
        return Arr::exists($data, $key) ? $data[$key] : $fallback;
    }
}
