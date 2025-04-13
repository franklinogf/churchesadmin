<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\Tenants\PermissionSeeder;
use Database\Seeders\Tenants\RoleSeeder;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('users.index'))
        ->assertRedirect(route('login'));
});

it('can be rendered if authenticated user has permission', function (): void {
    $this->seed([PermissionSeeder::class, RoleSeeder::class]);
    $user = User::factory()->superAdmin()->create();
    User::factory()->admin()->create();
    User::factory()->secretary()->create();
    User::factory()->noRole()->create();

    actingAs($user)
        ->get(route('users.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('users/index')
            ->has('users', 3)
        );
});

it('cannot be rendered if authenticated user does not have permission', function (): void {

    asUserWithoutPermission()
        ->get(route('users.index'))
        ->assertForbidden();
});
