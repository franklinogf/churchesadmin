<?php

declare(strict_types=1);

namespace App\Http\Resources\CurrentYear;

use App\Models\CurrentYear;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CurrentYear
 */
final class CurrentYearResource extends JsonResource
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
            'year' => $this->year,
            'startDate' => $this->start_date?->toFormattedDateString(),
            'endDate' => $this->end_date?->toFormattedDateString(),
            'isCurrent' => $this->is_current,
            'createdAt' => $this->created_at->toFormattedDateString(),
            'updatedAt' => $this->updated_at->toFormattedDateString(),
            'previousYear' => $this->previousYear(),
        ];
    }
}
