<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\CurrentYear;
use App\Models\TenantUser;
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
        DB::transaction(function () use ($data, $roles, $permissions): void {
            $user = TenantUser::create([...$data, 'current_year_id' => CurrentYear::current()->id]);

            if ($roles !== null) {
                $user->assignRole($roles);
            }

            if ($permissions !== null) {
                $user->givePermissionTo($permissions);
            }
        });

    }
}
