<?php

declare(strict_types=1);

namespace App\Support;

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
        return array_key_exists($key, $data) ? $data[$key] : $fallback;
    }
}
