<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\ModelMorphName;
use App\Models\Member;
use App\Support\DiffLogger;
use Illuminate\Support\Facades\DB;

final class DeactivateMemberAction
{
    public function handle(Member $member, int|string $deactivationCodeId): void
    {
        $logger = new DiffLogger();
        DB::transaction(function () use ($member, $deactivationCodeId, $logger): void {
            $member->update([
                'active' => false,
                'deactivation_code_id' => $deactivationCodeId,
            ]);

            $member->refresh();

            $logger->addCustom('active', true, false);
            $logger->addCustom('deactivation_code', null, $member->deactivationCode->name);
            activity(ModelMorphName::MEMBER->activityLogName())
                ->event('deactivated')
                ->performedOn($member)
                ->withProperties($logger->get())
                ->log('Member deactivated');

        });
    }
}
