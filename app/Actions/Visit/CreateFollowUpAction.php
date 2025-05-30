<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Enums\FollowUpType;
use App\Models\FollowUp;
use App\Models\Visit;

final class CreateFollowUpAction
{
    /**
     * Handle the action to create a follow-up for a visit.
     *
     * @param  array{member_id:string,type:FollowUpType,follow_up_at:string,notes?:string|null}  $data
     */
    public function handle(Visit $visit, array $data): FollowUp
    {
        return $visit->followUps()->create([
            'member_id' => $data['member_id'],
            'type' => $data['type'],
            'follow_up_at' => $data['follow_up_at'],
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
