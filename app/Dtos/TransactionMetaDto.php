<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Enums\TransactionMetaType;
use App\Models\CurrentYear;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @implements Arrayable<string,string>
 */
final class TransactionMetaDto implements Arrayable, JsonSerializable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly TransactionMetaType $type,
        public ?int $year = null
    ) {
        if ($this->year === null) {
            $this->year = CurrentYear::current()->id;
        }
    }

    /**
     * Get the instance as an array.
     *
     * @return array{type:string,year:int|null}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'year' => $this->year,
        ];
    }

    /**
     * Specify the data which should be serialized to JSON.
     *
     * @return array{type:string,year:int|null}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
