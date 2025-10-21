<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\ModelMorphName;
use App\Models\Member;
use App\Support\DiffLogger;
use Illuminate\Support\Facades\DB;

final class ActivateMemberAction
{
    public function handle(Member $member): void
    {
        $logger = new DiffLogger();
        DB::transaction(function () use ($member, $logger): void {

            $deactivationCode = $member->deactivationCode?->name;
            $member->update([
                'active' => true,
                'deactivation_code_id' => null,
            ]);

            $logger->addCustom('active', false, true);
            $logger->addCustom('deactivation_code', $deactivationCode, null);

            activity(ModelMorphName::MEMBER->activityLogName())
                ->event('activated')
                ->performedOn($member)
                ->withProperties($logger->get())
                ->log('Member activated');

        });
    }
}
