<?php

declare(strict_types=1);

namespace App\Dtos;

use DragonCode\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class WalletMetaDto implements JsonSerializable, Arrayable
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
