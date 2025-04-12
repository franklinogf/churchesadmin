<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Models\Member;

final class RestoreMemberAction
{
    /**
     * Handle the action.
     */
    public function handle(Member $member): void
    {
        $member->restore();
    }
}
