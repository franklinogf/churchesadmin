<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'startAt' => $this->start_at->toISOString(),
            'endAt' => $this->end_at->toISOString(),
            'createdBy' => $this->created_by,
            'createdAt' => $this->created_at->inUserTimezone()->formatAsDatetime(),
            'updatedAt' => $this->updated_at->inUserTimezone()->formatAsDatetime(),
            'creator' => $this->whenLoaded('creator', fn (): array => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
            ]),
        ];
    }
}
