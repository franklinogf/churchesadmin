<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Enums\TransactionMetaType;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @implements Arrayable<string,string>
 */
final readonly class TransactionMetaDto implements Arrayable, JsonSerializable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public TransactionMetaType $type,
    ) {}

    /**
     * Get the instance as an array.
     *
     * @return array{type:string}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
        ];
    }

    /**
     * Specify the data which should be serialized to JSON.
     *
     * @return array{type:string}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
