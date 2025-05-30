<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Enums\FollowUpType;
use App\Models\FollowUp;
use App\Support\ArrayFallback;

final class UpdateFollowUpAction
{
    /**
     * Handle the action to create a follow-up for a visit.
     *
     * @param  array{member_id?:string,type?:FollowUpType,follow_up_at?:string,notes?:string|null}  $data
     */
    public function handle(FollowUp $followUp, array $data): FollowUp
    {
        $followUp->update([
            'member_id' => $data['member_id'] ?? $followUp->member_id,
            'type' => $data['type'] ?? $followUp->type,
            'follow_up_at' => $data['follow_up_at'] ?? $followUp->follow_up_at,
            'notes' => ArrayFallback::inputOrFallback(
                $data,
                'notes',
                $followUp->notes,
            ),
        ]);

        return $followUp;
    }
}
