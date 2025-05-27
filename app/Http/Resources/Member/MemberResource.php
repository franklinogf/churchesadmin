<?php

declare(strict_types=1);

namespace App\Http\Resources\Member;

use App\Enums\TagType;
use App\Http\Resources\Address\AddressRelationshipResource;
use App\Http\Resources\Communication\Email\EmailableResource;
use App\Http\Resources\Communication\Email\EmailResource;
use App\Http\Resources\Tag\TagRelationshipResource;
use App\Models\Emailable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'civilStatus' => $this->civil_status->value,
            'skills' => TagRelationshipResource::collection($this->tagsWithType(TagType::SKILL->value)),
            'skillsCount' => $this->whenCounted('skills', $this->tagsWithType(TagType::SKILL->value)->count()),
            'categories' => TagRelationshipResource::collection($this->tagsWithType(TagType::CATEGORY->value)),
            'categoriesCount' => $this->whenCounted('categories', $this->tagsWithType(TagType::CATEGORY->value)->count()),
            'address' => new AddressRelationshipResource($this->whenLoaded('address')),
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d H:i:s'),
            'emails' => EmailResource::collection($this->whenLoaded('emails')),
            'emailMessage' => $this->whenPivotLoadedAs('emailMessage', new Emailable, fn (): EmailableResource => new EmailableResource($this->emailMessage)),
        ];
    }
}
