<?php

declare(strict_types=1);

namespace App\Http\Resources\Wallet;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Transaction
 */
final class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => $this->type,
            'amount' => $this->amount,
            'amountFloat' => $this->amountFloat,
            'confirmed' => $this->confirmed,
            'wallet' => new ChurchWalletResource($this->whenLoaded('wallet', fn () => $this->wallet->holder)),
            'meta' => $this->meta,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,
        ];
    }
}
