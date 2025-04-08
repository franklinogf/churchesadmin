<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Tag */
final class TagResource extends JsonResource
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
            'nameTranslations' => $this->getTranslations('name'),
            'slug' => $this->slug,
            'slugTranslations' => $this->getTranslations('slug'),
            'type' => $this->type,
            'orderColumn' => $this->order_column,
            'isRegular' => $this->is_regular,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
