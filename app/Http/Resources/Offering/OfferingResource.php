<?php

declare(strict_types=1);

namespace App\Http\Resources\Offering;

use App\Enums\OfferingType;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Transaction
 */
final class OfferingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $existPayer = $this->meta !== null
        && $this->meta->payer_id !== null;

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'wallet' => new WalletResource($this->whenLoaded('wallet')),
            'type' => $this->meta->offering_type !== null ? OfferingType::tryFrom($this->meta->offering_type)->label() : null,
            'date' => $this->meta->date !== null ? new Carbon($this->meta->date)->format('Y-m-d') : null,
            'amount' => $this->amount,
            'amountFloat' => $this->amountFloat,
            'confirmed' => $this->confirmed,
            'payer' => $this->when($existPayer, fn (): MemberResource => new MemberResource(Member::find($this->meta->payer_id))),
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
