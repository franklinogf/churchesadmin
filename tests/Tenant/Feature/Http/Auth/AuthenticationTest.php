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

test('users cannot authenticate with invalid password', function (): void {
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

describe('guest middleware protection', function (): void {
    test('authenticated users cannot access login page', function (): void {
        $user = TenantUser::factory()->create();

        actingAs($user, 'tenant')
            ->get(route('login'))
            ->assertRedirect(route('dashboard', absolute: false));
    });

    test('authenticated users cannot submit login form', function (): void {
        $user = TenantUser::factory()->create();

        actingAs($user, 'tenant')
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('dashboard', absolute: false));
    });
});

describe('session management', function (): void {
    test('session is regenerated on successful login', function (): void {
        $user = TenantUser::factory()->create();

        $response = post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        assertAuthenticated('tenant');
    });

    test('session is invalidated on logout', function (): void {
        $user = TenantUser::factory()->create();

        $response = actingAs($user, 'tenant')->post(route('logout'));

        $response->assertRedirect(route('login'));
        assertGuest();
    });
});

describe('intended redirect functionality', function (): void {
    test('users are redirected to intended route after login', function (): void {
        $user = TenantUser::factory()->create();

        // Simulate trying to access a protected route
        session(['url.intended' => route('dashboard')]);

        post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect(route('dashboard', absolute: false));

        assertAuthenticated('tenant');
    });
});

describe('validation errors', function (): void {
    test('login requires email', function (): void {
        post(route('login.store'), [
            'password' => 'password',
        ])
            ->assertSessionHasErrors(['email']);

        assertGuest();
    });

    test('login requires password', function (): void {
        post(route('login.store'), [
            'email' => 'test@example.com',
        ])
            ->assertSessionHasErrors(['password']);

        assertGuest();
    });

    test('login requires valid email format', function (): void {
        post(route('login.store'), [
            'email' => 'invalid-email',
            'password' => 'password',
        ])
            ->assertSessionHasErrors(['email']);

        assertGuest();
    });
});
