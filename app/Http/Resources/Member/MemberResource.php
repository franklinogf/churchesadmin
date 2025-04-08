<?php

declare(strict_types=1);

namespace App\Http\Resources\Member;

use App\Enums\TagType;
use App\Http\Resources\TagResource;
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
            'dob' => $this->dob->format('Y-m-d'),
            'civilStatus' => $this->civil_status->value,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'skills' => TagResource::collection($this->tagsWithType(TagType::SKILL->value)),
            'categories' => TagResource::collection($this->tagsWithType(TagType::CATEGORY->value)),
        ];
    }
}
