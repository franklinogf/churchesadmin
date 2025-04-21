<?php

declare(strict_types=1);

namespace App\Casts;

use App\Dtos\WalletMetaDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class WalletMeta implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        $value = json_decode($value, true);

        return new WalletMetaDto(
            bank_name: $value['bank_name'],
            bank_routing_number: $value['bank_routing_number'],
            bank_account_number: $value['bank_account_number'],

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
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): string
    {
        return json_encode($value);
    }
}
