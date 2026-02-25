<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\ModelMorphName;
use App\Models\CurrentYear;
use App\Models\TenantUser;
use App\Support\DiffLogger;
use Illuminate\Support\Facades\DB;

final class CreateUserAction
{
    /**
     * Handle the action.
     *
     * @param  array<string,mixed>  $data
     * @param  array<int,string>|null  $roles
     * @param  array<int,string>|null  $permissions
     */
    public function handle(array $data, ?array $roles = null, ?array $permissions = null): void
    {
        $logger = new DiffLogger();
        DB::transaction(function () use ($data, $roles, $permissions, $logger): void {
            $user = TenantUser::create([...$data, 'current_year_id' => CurrentYear::current()->id]);
            $logger->addChanges([], $user->only(['name', 'email', 'active']));
            if ($roles !== null) {
                $user->assignRole($roles);
                $logger->addCustom('roles', null, $roles);
            }

            if ($permissions !== null) {
                $user->givePermissionTo($permissions);
                $logger->addCustom('permissions', null, $permissions);
            }

            activity(ModelMorphName::USER->activityLogName())
                ->event('created')
                ->performedOn($user)
                ->withProperties($logger->get())
                ->log('User created');
        });

    }
}
