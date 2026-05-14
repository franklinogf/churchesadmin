<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

use function is_string;

/**
 * @implements CastsAttributes<string,string>
 */
final class AsUcWords implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('The value must be a string.');
        }

        return ucwords(mb_strtolower(mb_trim($value)));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('The value must be a string.');
        }

        return ucwords(mb_strtolower(mb_trim($value)));

    }
}
