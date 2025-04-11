<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class UpdateUserAction
{
    public function handle(User $user, array $data, ?array $roles = null, ?array $permissions = null): void
    {
        DB::transaction(function () use ($user, $data, $roles, $permissions) {
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
