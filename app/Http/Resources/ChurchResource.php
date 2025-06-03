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
            'id' => $this->id,
            'name' => $this->name,
            'locale' => $this->locale,
            'createdAt' => $this->created_at->inUserTimezone()->formatAsDatetime(),
            'updatedAt' => $this->updated_at->inUserTimezone()->formatAsDatetime(),
            'logo' => ($logo = $this->getFirstMediaUrl(MediaCollectionName::LOGO->value)) === '' ? null : $logo,
            'active' => $this->active,
        ];
    }
}
