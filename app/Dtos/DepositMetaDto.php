<?php

declare(strict_types=1);

namespace App\Dtos;

final readonly class DepositMetaDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?string $payer_id = null,
        public ?string $date = null,
        public ?string $offering_type = null,
        public ?string $message = null,
    ) {
        //
    }

    /**
     * Convert the class instance to an array.
     *
     * @return array{payer_id:string|null,date:string|null,offering_type:string|null,message:string|null}
     */
    public function toArray(): array
    {
        return [
            'payer_id' => $this->payer_id,
            'date' => $this->date,
            'offering_type' => $this->offering_type,
            'message' => $this->message,
        ];
    }
}
