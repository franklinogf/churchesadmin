<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\MediaCollectionName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Church
 */
final class ChurchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'locale' => $this->locale,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'logo' => $this->getFirstMediaUrl(MediaCollectionName::LOGO->value),
            'active' => $this->active,
        ];
    }
}
