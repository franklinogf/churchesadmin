<?php

declare(strict_types=1);

namespace App\Http\Resources\Offering;

use App\Http\Resources\Codes\OfferingTypeResource;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Missionary\MissionaryResource;
use App\Http\Resources\Wallet\TransactionResource;
use App\Models\Missionary;
use App\Models\OfferingType;
use Illuminate\Database\Eloquent\Relations\Relation;
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
            'date' => $this->date->format('Y-m-d'),
            'paymentMethod' => $this->payment_method,
            'offeringType' => match ($this->offering_type_type) {
                Relation::getMorphAlias(OfferingType::class) => new OfferingTypeResource($this->offeringType),
                Relation::getMorphAlias(Missionary::class) => new MissionaryResource($this->offeringType),
                default => null,
            },
            'offeringTypeModel' => $this->offering_type_type,
            'note' => $this->note,
        ];
    }
}
