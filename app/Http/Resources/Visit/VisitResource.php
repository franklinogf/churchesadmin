<?php

declare(strict_types=1);

namespace App\Http\Resources\Visit;

use App\Http\Resources\Address\AddressRelationshipResource;
use App\Http\Resources\Communication\Email\EmailableResource;
use App\Http\Resources\Communication\Email\EmailResource;
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
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastName' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'firstVisitDate' => $this->first_visit_date?->format('Y-m-d'),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,
            'address' => new AddressRelationshipResource($this->whenLoaded('address')),
            'followUps' => FollowUpResource::collection($this->whenLoaded('followUps')),
            'lastFollowUp' => new FollowUpResource($this->whenLoaded('lastFollowUp')),
            'emails' => EmailResource::collection($this->whenLoaded('emails')),
            'emailMessage' => $this->whenPivotLoadedAs('emailMessage', 'emailables', fn (): EmailableResource => new EmailableResource($this->emailMessage)),
        ];
    }
}
