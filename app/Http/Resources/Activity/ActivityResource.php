<?php

declare(strict_types=1);

namespace App\Http\Resources\Activity;

use App\Enums\ModelMorphName;
use App\Http\Resources\Check\CheckResource;
use App\Http\Resources\Expense\ExpenseResource;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Missionary\MissionaryResource;
use App\Http\Resources\Offering\OfferingResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Visit\VisitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;
use Spatie\Activitylog\Models\Activity;

/**
 * @mixin Activity
 */
final class ActivityResource extends JsonResource
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
            'batchUuid' => $this->batch_uuid,
            'event' => $this->event,
            'logName' => $this->log_name,
            'description' => $this->description,
            'subjectType' => $this->subject_type,
            'subjectId' => $this->subject_id,
            'causerType' => $this->causer_type,
            'causerId' => $this->causer_id,
            'properties' => $this->properties,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

            'causer' => $this->whenLoaded('causer', fn (): ?UserResource => $this->causer ? new UserResource($this->causer) : null),
            'subject' => $this->whenLoaded('subject', fn (): CheckResource|ExpenseResource|MemberResource|MissionaryResource|OfferingResource|UserResource|VisitResource|null => $this->subject ?
            match ($this->subject_type) {
                ModelMorphName::MEMBER->value => new MemberResource($this->subject),
                ModelMorphName::MISSIONARY->value => new MissionaryResource($this->subject),
                ModelMorphName::VISIT->value => new VisitResource($this->subject),
                ModelMorphName::USER->value => new UserResource($this->subject),
                ModelMorphName::OFFERING->value => new OfferingResource($this->subject),
                ModelMorphName::EXPENSE->value => new ExpenseResource($this->subject),
                ModelMorphName::CHECK->value => new CheckResource($this->subject),
                default => null,
            } : null),
        ];
    }
}
