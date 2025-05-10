<?php

declare(strict_types=1);

namespace App\Http\Resources\Wallet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Transaction
 */
final class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => $this->type,
            'amount' => $this->amount,
            'amountFloat' => $this->amountFloat,
            'confirmed' => $this->confirmed,
            'wallet' => new ChurchWalletResource($this->whenLoaded('wallet')),
            'meta' => $this->meta,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
