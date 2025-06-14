<?php

declare(strict_types=1);

namespace App\Http\Resources\Communication\Email;

use App\Models\Emailable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Emailable
 */
final class EmailableResource extends JsonResource
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
            'sentAt' => $this->sent_at?->inUserTimezone()->formatAsDatetime(),
            'status' => $this->status->value,
            'replyTo' => $this->email->reply_to,
            'errorMessage' => $this->error_message,
            'createdAt' => $this->created_at->inUserTimezone()->formatAsDatetime(),
            'updatedAt' => $this->updated_at->inUserTimezone()->formatAsDatetime(),
        ];
    }
}
