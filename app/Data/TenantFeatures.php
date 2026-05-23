<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @implements Arrayable<string, bool>
 */
final readonly class TenantFeatures implements Arrayable, JsonSerializable
{
    public function __construct(
        public bool $books = false,
    ) {}

    /**
     * @param  array{books?: bool}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            books: $data['books'] ?? false,
        );
    }

    /**
     * @return array{books: bool}
     */
    public function toArray(): array
    {
        return [
            'books' => $this->books,
        ];
    }

    /**
     * @return array{books: bool}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
