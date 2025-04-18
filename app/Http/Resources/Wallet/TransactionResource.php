<?php

declare(strict_types=1);

namespace App\Http\Resources\Wallet;

use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Missionary\MissionaryResource;
use App\Models\Member;
use App\Models\Missionary;
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
            'payerType' => $this->when($this->relationLoaded('payer'), fn () => $this->payer_type),
            'payer' => $this->when($this->relationLoaded('payer'), function () {
                return match (true) {
                    $this->payer instanceof Member => new MemberResource($this->payer),
                    $this->payer instanceof Missionary => new MissionaryResource($this->payer),
                    default => null,
                };
            }),
            'type' => $this->type,
            'amount' => $this->amount,
            'amountFloat' => $this->amountFloat,
            'confirmed' => $this->confirmed,
            'meta' => $this->meta,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
