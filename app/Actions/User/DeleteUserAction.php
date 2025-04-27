<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\TenantUser;

final class DeleteUserAction
{
    /**
     * Handle the action.
     */
    public function handle(TenantUser $user): void
    {
        $user->delete();
    }
}
