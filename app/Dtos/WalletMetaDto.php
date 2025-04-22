<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Casts\WalletMeta;
use Illuminate\Contracts\Database\Eloquent\Castable;

/**
 * @property-read string|null $payer_id
 * @property-read string|null $date
 * @property-read string $offering_type
 * @property-read string|null $message
 */
final readonly class WalletMetaDto implements Castable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $bank_name,
        public string $bank_routing_number,
        public string $bank_account_number,
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
        return WalletMeta::class;
    }

    /**
     * Convert the class instance to an array.
     *
     * @return array{bank_name:string,bank_routing_number:string,bank_account_number:string}
     */
    public function toArray(): array
    {
        return [
            'bank_name' => $this->bank_name,
            'bank_routing_number' => $this->bank_routing_number,
            'bank_account_number' => $this->bank_account_number,
        ];
    }
}
