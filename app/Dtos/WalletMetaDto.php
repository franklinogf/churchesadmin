<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Casts\AsWalletMeta;
use DragonCode\Contracts\Support\Arrayable;
use Illuminate\Contracts\Database\Eloquent\Castable;
use JsonSerializable;

/**
 * @property-read string|null $payer_id
 * @property-read string|null $date
 * @property-read string $offering_type
 * @property-read string|null $message
 */
final readonly class WalletMetaDto implements JsonSerializable, Arrayable, Castable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $bankName,
        public string $bankRoutingNumber,
        public string $bankAccountNumber,
    ) {
        //
    }

    /**
     * Get the class name that should be used for casting.
     *
     * @param  array<string, mixed>  $arguments
     * @return string
     */
    public static function castUsing(array $arguments): string
    {
        return AsWalletMeta::class;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{bank_name:string,bank_routing_number:string,bank_account_number:string}
     */
    public function toArray(): array
    {
        return [
            'bank_name' => $this->bankName,
            'bank_routing_number' => $this->bankRoutingNumber,
            'bank_account_number' => $this->bankAccountNumber,
        ];
    }

    /**
     * Specify the data which should be serialized to JSON.
     *
     * @return array{bank_name:string,bank_routing_number:string,bank_account_number:string}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
