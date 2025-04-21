<?php

declare(strict_types=1);

namespace App\Http\Resources\Wallet;

use App\Http\Resources\Member\MemberResource;
use App\Models\Member;
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
        $existPayer = $this->meta !== null && $this->meta->payer_id !== null;

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => $this->type,
            'amount' => $this->amount,
            'amountFloat' => $this->amountFloat,
            'confirmed' => $this->confirmed,
            'meta' => [
                'offeringType' => $this->meta->offering_type,
                'message' => $this->meta?->message,
                'payerId' => $this->meta?->payer_id,
                'date' => $this->meta?->date,
            ],
            'payer' => $this->when($existPayer, fn (): MemberResource => new MemberResource(Member::find($this->meta->payer_id))),
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
