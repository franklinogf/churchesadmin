<?php

declare(strict_types=1);

namespace App\Http\Resources\Visit;

use App\Http\Resources\Address\AddressResource;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Visit
 */
final class VisitResource extends JsonResource
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
            'name' => $this->name,
            'lastName' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
            'address' => new AddressResource($this->whenLoaded('address')),
            'followUps' => FollowUpResource::collection($this->whenLoaded('followUps')),
            'lastFollowUp' => new FollowUpResource($this->whenLoaded('lastFollowUp')),
        ];
    }
}
