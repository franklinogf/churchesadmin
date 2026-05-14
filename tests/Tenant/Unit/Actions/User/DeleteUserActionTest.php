<?php

declare(strict_types=1);

use App\Actions\User\DeleteUserAction;
use App\Models\TenantUser;

it('can delete a user', function (): void {
    $user = TenantUser::factory()->create();

    $action = new DeleteUserAction();
    $action->handle($user);

    expect(TenantUser::count())->toBe(0);
});
