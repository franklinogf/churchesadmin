<?php

declare(strict_types=1);

use App\Models\TenantUser;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('login screen can be rendered', function (): void {
    get(route('login'))->assertOk()
        ->assertInertia(
            fn (Assert $page): Assert => $page
                ->component('auth/login')
        );
});

test('users can authenticate using the login screen', function (): void {
    $user = TenantUser::factory()->create();

    post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertRedirect(route('dashboard', absolute: false));

    assertAuthenticated();

});

test('users can not authenticate with invalid password', function (): void {
    $user = TenantUser::factory()->create();

    post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])
        ->assertSessionHas('errors');

    assertGuest();
});

test('users can logout', function (): void {
    $user = TenantUser::factory()->create();

    actingAs($user, 'tenant')->post(route('logout'))->assertRedirect(route('login'));

    assertGuest();

});
