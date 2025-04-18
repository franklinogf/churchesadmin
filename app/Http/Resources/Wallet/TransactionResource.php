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
        $existPayer = $this->meta !== null
        && ($this->meta['payer_type'] && $this->meta['payer_type'] !== null)
        && ($this->meta['payer_id'] && $this->meta['payer_id'] !== null);

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => $this->type,
            'amount' => $this->amount,
            'amountFloat' => $this->amountFloat,
            'confirmed' => $this->confirmed,
            'meta' => $this->meta,
            'payerType' => $this->when($existPayer, fn () => $this->meta['payer_type']),
            'payer' => $this->when($existPayer, fn (): MemberResource|MissionaryResource|null => match (true) {
                $this->meta['payer_type'] === (new Member)->getMorphClass() => new MemberResource(Member::find($this->meta['payer_id'])),
                $this->meta['payer_type'] === (new Missionary)->getMorphClass() => new MissionaryResource(Missionary::find($this->meta['payer_id'])),
                default => null,
            }),
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
