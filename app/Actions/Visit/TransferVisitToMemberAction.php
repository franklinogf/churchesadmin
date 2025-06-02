<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Models\Member;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

final class TransferVisitToMemberAction
{
    /**
     * Handle the action.
     */
    public function handle(Visit $visit, Member $member): Member
    {
        return DB::transaction(function () use ($visit, $member): Member {
            $visit->delete();
            $member->update(['visit_id' => $visit->id]);

            return $member;
        });

    }
}
