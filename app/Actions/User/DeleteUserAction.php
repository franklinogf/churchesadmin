<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\ModelMorphName;
use App\Models\TenantUser;
use Illuminate\Support\Facades\DB;

final class DeleteUserAction
{
    /**
     * Handle the action.
     */
    public function handle(TenantUser $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->delete();
            activity(ModelMorphName::USER->activityLogName())
                ->event('deleted')
                ->performedOn($user)
                ->log('User :subject.name deleted');
        });
    }
}
