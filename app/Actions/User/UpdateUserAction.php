<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\ModelMorphName;
use App\Models\TenantUser;
use App\Support\DiffLogger;
use Illuminate\Support\Facades\DB;

final class UpdateUserAction
{
    /**
     * Handle the action.
     *
     * @param  array<string,mixed>|array{}  $data
     * @param  array<int,string>|null  $roles
     * @param  array<int,string>|null  $permissions
     */
    public function handle(TenantUser $user, array $data = [], ?array $roles = null, ?array $permissions = null): void
    {
        DB::transaction(function () use ($user, $data, $roles, $permissions): void {
            $logger = new DiffLogger();
            $originalUser = $user->replicate();
            /** @var array<string, array<string>> */
            $originalRoles = $user->roles->pluck('name')->toArray();
            $originalPermissions = $user->permissions->pluck('name')->toArray();
            if ($data !== []) {
                $user->update($data);
                $freshUser = $user->fresh();
                if ($freshUser !== null) {

                    $logger->compareModels($originalUser, $freshUser, [
                        'name', 'email',
                    ]);
                }
            }

            if ($roles !== null) {
                $user->syncRoles($roles);
                $logger->addCustom('roles', $originalRoles, $roles);
            }

            if ($permissions !== null) {
                $user->syncPermissions($permissions);
                $logger->addCustom('permissions', $originalPermissions, $permissions);
            }

            if ($logger->hasChanges()) {
                activity(ModelMorphName::USER->activityLogName())
                    ->event('updated')
                    ->performedOn($user)
                    ->withProperties($logger->get())
                    ->log($logger->getSummary());
            }
        });
    }
}
