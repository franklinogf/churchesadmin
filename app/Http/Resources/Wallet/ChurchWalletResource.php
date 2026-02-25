<?php

declare(strict_types=1);

namespace App\Http\Resources\Wallet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ChurchWallet
 */
final class ChurchWalletResource extends JsonResource
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
            'bankName' => $this->bank_name,
            'bankRoutingNumber' => $this->bank_routing_number,
            'bankAccountNumber' => $this->bank_account_number,
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'balance' => $this->balance,
            'balanceNumber' => $this->balanceInt,
            'balanceFloat' => $this->balanceFloat,
            'balanceFloatNumber' => $this->balanceFloatNum,
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'transactionsCount' => $this->whenCounted('transactions'),
            'checkLayout' => new CheckLayoutResource($this->whenLoaded('checkLayout')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,

        ];
    }
}
