<?php

declare(strict_types=1);

namespace App\Http\Resources\Visit;

use App\Http\Resources\Member\MemberResource;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin FollowUp
 */
final class FollowUpResource extends JsonResource
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
            'visitId' => $this->visit_id,
            'visit' => new VisitResource($this->whenLoaded('visit')),
            'memberId' => $this->member_id,
            'member' => new MemberResource($this->whenLoaded('member')),
            'type' => $this->type->value,
            'followUpAt' => $this->follow_up_at->format('Y-m-d H:i'),
            'notes' => $this->notes,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
