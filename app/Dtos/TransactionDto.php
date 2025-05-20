<?php

declare(strict_types=1);

namespace App\Dtos;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @implements Arrayable<string,string>
 */
final readonly class TransactionDto implements JsonSerializable, Arrayable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $amount,
        public TransactionMetaDto $meta,
        public bool $confirmed = true,
    ) {}

    /**
     * Get the instance as an array.
     *
     * @return array{meta:array{type:string},amount:string,confirmed:bool}
     */
    public function toArray(): array
    {
        return [
            'meta' => $this->meta->toArray(),
            'amount' => $this->amount,
            'confirmed' => $this->confirmed,
        ];
    }

    /**
     * Specify the data which should be serialized to JSON.
     *
     * @return array{meta:array{type:string},amount:string,confirmed:bool}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
