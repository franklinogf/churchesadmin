<?php

declare(strict_types=1);

namespace App\Dtos;

final class DepositMetaDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected readonly int $payer_id,
        protected readonly string $payer_type,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'payer_id' => $this->payer_id,
            'payer_type' => $this->payer_type,
        ];
    }
}
