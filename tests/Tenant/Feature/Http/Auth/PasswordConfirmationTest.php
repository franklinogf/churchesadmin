<?php

declare(strict_types=1);

use App\Models\TenantUser;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('confirm password screen can be rendered', function (): void {
    $user = TenantUser::factory()->create();

    $response = $this->actingAs($user)->get(route('password.confirm'));

    $response->assertStatus(200);
});

test('password can be confirmed', function (): void {
    $user = TenantUser::factory()->create();

    $response = $this->actingAs($user)->post(route('password.confirm.update'), [
        'password' => 'password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function (): void {
    $user = TenantUser::factory()->create();

    $response = $this->actingAs($user)->post(route('password.confirm.update'), [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
});

describe('guest middleware protection', function (): void {
    test('guest cannot access password confirmation screen', function (): void {
        get(route('password.confirm'))
            ->assertRedirect(route('login'));
    });

    test('guest cannot submit password confirmation', function (): void {
        post(route('password.confirm.update'), [
            'password' => 'password',
        ])
            ->assertRedirect(route('login'));
    });
});

describe('intended redirect functionality', function (): void {
    test('user is redirected to intended route after password confirmation', function (): void {
        $user = TenantUser::factory()->create();

        // Simulate intended route
        session(['url.intended' => route('dashboard')]);

        $response = $this->actingAs($user)->post(route('password.confirm.update'), [
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
    });
});

describe('validation', function (): void {
    test('password confirmation requires password field', function (): void {
        $user = TenantUser::factory()->create();

        $response = $this->actingAs($user)->post(route('password.confirm.update'), []);

        $response->assertSessionHasErrors(['password']);
    });
});

describe('session state management', function (): void {
    test('password confirmation timestamp is stored in session', function (): void {
        $user = TenantUser::factory()->create();

        $this->actingAs($user)->post(route('password.confirm.update'), [
            'password' => 'password',
        ]);

        expect(session('auth.password_confirmed_at'))->toBeNumeric();
    });
});
