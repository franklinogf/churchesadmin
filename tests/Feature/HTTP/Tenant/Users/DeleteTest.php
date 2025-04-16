<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\User;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted if user has permission', function (): void {
    $user = User::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_USERS, TenantPermission::DELETE_USERS)
        ->from(route('users.index'))
        ->delete(route('users.destroy', ['user' => $user]))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('users', 1);

});

it('cannot be deleted if user does not have permission', function (): void {
    $user = User::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_USERS)
        ->from(route('users.index'))
        ->delete(route('users.destroy', ['user' => $user]))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas(FlashMessageKey::ERROR->value);

    assertDatabaseCount('users', 2);
});
