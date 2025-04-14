<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\LanguageCode;
use App\Models\User;
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
            $user = User::create([
                'language' => LanguageCode::EN->value,
                ...$data,
            ]);

            if ($roles !== null) {
                $user->assignRole($roles);
            }

            if ($permissions !== null) {
                $user->givePermissionTo($permissions);
            }
        });

    }
}
