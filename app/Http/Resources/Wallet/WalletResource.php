<?php

declare(strict_types=1);

namespace App\Http\Resources\Wallet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Wallet
 */
final class WalletResource extends JsonResource
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
            'meta' => $this->meta !== null ? [
                'bankName' => $this->meta['bank_name'] ?? null,
                'bankRoutingNumber' => $this->meta['bank_routing_number'] ?? null,
                'bankAccountNumber' => $this->meta['bank_account_number'] ?? null,
            ] : null,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'balance' => $this->balance,
            'balanceNumber' => $this->balanceInt,
            'balanceFloat' => $this->balanceFloat,
            'balanceFloatNumber' => $this->balanceFloatNum,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
            'transactions' => TransactionResource::collection($this->whenLoaded('walletTransactions')),
            'transactionsCount' => $this->whenCounted('walletTransactions'),

        ];
    }
}
