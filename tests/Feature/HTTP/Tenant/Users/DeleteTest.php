<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\TenantUser;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted if user has permission', function (): void {
    $user = TenantUser::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::USERS_MANAGE, TenantPermission::USERS_DELETE)
        ->from(route('users.index'))
        ->delete(route('users.destroy', ['user' => $user]))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('users', 1);

});

it('cannot be deleted if user does not have permission', function (): void {
    $user = TenantUser::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::USERS_MANAGE)
        ->from(route('users.index'))
        ->delete(route('users.destroy', ['user' => $user]))
        ->assertForbidden();

    assertDatabaseCount('users', 2);
});
