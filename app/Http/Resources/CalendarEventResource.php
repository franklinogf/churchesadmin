<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin CalendarEvent
 */
final class CalendarEventResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'color' => $this->color,
            'startAt' => $this->start_at->toISOString(),
            'endAt' => $this->end_at->toISOString(),
            'createdBy' => $this->created_by,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'creator' => $this->whenLoaded('creator'),
        ];
    }
}
