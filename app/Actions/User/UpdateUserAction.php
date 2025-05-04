<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\TenantUser;
use Illuminate\Support\Facades\DB;

final class UpdateUserAction
{
    /**
     * Handle the action.
     *
     * @param  array<string,mixed>  $data
     * @param  array<int,string>|null  $roles
     * @param  array<int,string>|null  $permissions
     */
    public function handle(TenantUser $user, array $data, ?array $roles = null, ?array $permissions = null): void
    {
        DB::transaction(function () use ($user, $data, $roles, $permissions): void {
            $user->update($data);

            if ($roles !== null) {
                $user->syncRoles($roles);
            }

            if ($permissions !== null) {
                $user->syncPermissions($permissions);
            }
        });
    }
}
