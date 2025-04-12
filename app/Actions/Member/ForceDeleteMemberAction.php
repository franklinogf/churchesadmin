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
        $member->forceDelete();
    }
}
