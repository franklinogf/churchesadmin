<?php

declare(strict_types=1);

namespace App\Http\Resources\Communication\Email;

use App\Enums\MediaCollectionName;
use App\Enums\ModelMorphName;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Missionary\MissionaryResource;
use App\Http\Resources\Spatie\MediaResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Visit\VisitResource;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Email
 */
final class EmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'body' => $this->body,
            'senderId' => $this->sender_id,
            'sender' => new UserResource($this->whenLoaded('sender')),
            'recipientsType' => $this->recipients_type,
            // 'members' => MemberResource::collection($this->whenLoaded('members')),
            // 'missionaries' => MissionaryResource::collection($this->whenLoaded('missionaries')),
            'recipients' => match ($this->recipients_type) {
                ModelMorphName::MEMBER => MemberResource::collection($this->members),
                ModelMorphName::MISSIONARY => MissionaryResource::collection($this->missionaries),
                ModelMorphName::VISIT => VisitResource::collection($this->visits),
                default => null,
            },
            'replyTo' => $this->reply_to,
            'status' => $this->status,
            'sentAt' => $this->sent_at,
            'errorMessage' => $this->error_message,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'attachments' => $this->whenLoaded('media', fn () => MediaResource::collection($this->getMedia(MediaCollectionName::ATTACHMENT->value))),
            'attachmentsCount' => $this->whenCounted('media', $this->getMedia(MediaCollectionName::ATTACHMENT->value)->count()),
            'message' => $this->whenPivotLoadedAs('message', 'emailables', fn (): EmailableResource => new EmailableResource($this->message)),
        ];
    }
}
