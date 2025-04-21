<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Casts\TransactionMeta;
use Illuminate\Contracts\Database\Eloquent\Castable;

/**
 * @property-read string|null $payer_id
 * @property-read string|null $date
 * @property-read string $offering_type
 * @property-read string|null $message
 */
final readonly class TransactionMetaDto implements Castable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $offering_type,
        public ?string $payer_id = null,
        public ?string $date = null,
        public ?string $message = null,
    ) {
        //
    }

    public static function castUsing(array $arguments): string
    {
        return TransactionMeta::class;
    }

    /**
     * Convert the class instance to an array.
     *
     * @return array{payer_id:string|null,date:string|null,offering_type:string,message:string|null}
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
