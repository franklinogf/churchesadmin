<?php

declare(strict_types=1);

namespace App\Http\Resources\Offering;

use App\Http\Resources\Codes\OfferingTypeResource;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Missionary\MissionaryResource;
use App\Http\Resources\Wallet\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Offering
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

        return [
            'id' => $this->id,
            'transaction' => new TransactionResource($this->transaction),
            'donor' => new MemberResource($this->donor),
            'recipient' => new MissionaryResource($this->recipient),
            'date' => $this->date->format('Y-m-d'),
            'paymentMethod' => $this->payment_method,
            'offeringType' => new OfferingTypeResource($this->offering_type),
            'note' => $this->note,
        ];
    }
}
