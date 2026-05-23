<?php

declare(strict_types=1);

namespace App\Casts;

use App\Data\TenantFeatures;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

use function is_array;
use function is_string;

/**
 * @implements CastsAttributes<TenantFeatures, mixed>
 */
final class TenantFeaturesCast implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): TenantFeatures
    {
        if (! is_string($value)) {
            return new TenantFeatures;
        }

        /** @var array{books?: bool}|null $decoded */
        $decoded = json_decode($value, true);

        if (! is_array($decoded)) {
            return new TenantFeatures;
        }

        return TenantFeatures::fromArray($decoded);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if ($value === null) {
            $value = new TenantFeatures;
        }

        if (is_array($value)) {
            /**
             * @var array{books?: bool} $valueArray
             */
            $valueArray = $value;
            $value = TenantFeatures::fromArray($valueArray);
        }

        throw_unless($value instanceof TenantFeatures, InvalidArgumentException::class, 'Value is not a TenantFeatures instance');

        $json = json_encode($value->toArray());

        if ($json === false) {
            return '{"books":false}';
        }

        return $json;
    }
}
