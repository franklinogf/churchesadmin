<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Models\Member;

final class ForceDeleteMemberAction
{
    /**
     * Handle the action.
     */
    public function handle(Member $member): void
    {
        // Manually delete addresses since force delete won't trigger model events
        if ($member->address) {
            $member->address->delete();
        }

        $member->forceDelete();
    }
}
