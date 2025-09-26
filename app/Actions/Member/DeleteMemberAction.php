<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Models\Member;
use Exception;
use Illuminate\Support\Facades\DB;

final class DeleteMemberAction
{
    /**
     * Handle the action.
     */
    public function handle(Member $member): void
    {
        try {
            DB::transaction(function () use ($member): void {
                // Delete associated address if exists
                if ($member->address) {
                    $member->address->delete();
                }
                $member->tags()->detach();
                $member->delete();
            });
        } catch (Exception $th) {
            throw new Exception('Failed to delete member: '.$th->getMessage(), $th->getCode(), $th);
        }
        activity('members')
            ->event('deleted')
            ->performedOn($member)
            ->log('Member :subject.name deleted');
    }
}
