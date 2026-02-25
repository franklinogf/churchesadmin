<?php

declare(strict_types=1);

namespace App\Http\Resources\Member;

use App\Enums\TagType;
use App\Http\Resources\Address\AddressRelationshipResource;
use App\Http\Resources\Communication\Email\EmailableResource;
use App\Http\Resources\Communication\Email\EmailResource;
use App\Http\Resources\DeactivationCode\DeactivationCodeResource;
use App\Http\Resources\Tag\TagRelationshipResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin \App\Models\Member
 */
final class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender->value,
            'dob' => $this->dob?->format('Y-m-d'),
            'baptismDate' => $this->baptism_date?->format('Y-m-d'),
            'civilStatus' => $this->civil_status->value,
            'active' => $this->active,
            'deactivationCodeId' => $this->deactivation_code_id,
            'deactivationCode' => $this->whenLoaded('deactivationCode', fn (): ?DeactivationCodeResource => $this->deactivationCode ? new DeactivationCodeResource($this->deactivationCode) : null),
            'skills' => TagRelationshipResource::collection($this->tagsWithType(TagType::SKILL->value)),
            'skillsCount' => $this->whenCounted('skills', $this->tagsWithType(TagType::SKILL->value)->count()),
            'categories' => TagRelationshipResource::collection($this->tagsWithType(TagType::CATEGORY->value)),
            'categoriesCount' => $this->whenCounted('categories', $this->tagsWithType(TagType::CATEGORY->value)->count()),
            'address' => new AddressRelationshipResource($this->whenLoaded('address')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'emails' => EmailResource::collection($this->whenLoaded('emails')),
            'emailMessage' => $this->whenPivotLoadedAs('emailMessage', 'emailables', fn (): EmailableResource => new EmailableResource($this->emailMessage)),
        ];
    }
}
