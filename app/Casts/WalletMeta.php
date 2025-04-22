<?php

declare(strict_types=1);

namespace App\Casts;

use App\Dtos\WalletMetaDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<WalletMetaDto|null,string>
 */
final class WalletMeta implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     * @param  string|null  $value
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?WalletMetaDto
    {
        if ($value === null) {
            return null;
        }
        /**
         * @var array{bank_name:string,bank_routing_number:string,bank_account_number:string} $valueData
         */
        $valueData = json_decode($value, true);

        return new WalletMetaDto(
            bank_name: $valueData['bank_name'],
            bank_routing_number: $valueData['bank_routing_number'],
            bank_account_number: $valueData['bank_account_number'],

        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [$key => json_encode($value)];
    }

    /**
     * Get the serialized representation of the value.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array{bank_name:string,bank_routing_number:string,bank_account_number:string}  $value
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): string
    {
        return json_encode($value) ?: '';
    }
}
