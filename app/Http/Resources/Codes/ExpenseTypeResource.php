<?php

declare(strict_types=1);

namespace App\Http\Resources\Codes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ExpenseType
 */
final class ExpenseTypeResource extends JsonResource
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
            'defaultAmount' => $this->default_amount,
            'createdAt' => $this->created_at->inUserTimezone()->formatAsDatetime(),
            'updatedAt' => $this->updated_at->inUserTimezone()->formatAsDatetime(),
        ];
    }
}
