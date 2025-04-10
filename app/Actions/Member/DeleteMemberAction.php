<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Models\Member;

final class DeleteMemberAction
{
    public function handle(Member $member): void
    {
        $member->delete();
    }
}
