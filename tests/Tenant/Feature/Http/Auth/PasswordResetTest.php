<?php

declare(strict_types=1);

use App\Models\TenantUser;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('reset password link screen can be rendered', function (): void {
    $response = $this->get(route('password.request'));

    $response->assertStatus(200);
});

test('reset password link can be requested', function (): void {
    Notification::fake();

    $user = TenantUser::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function (): void {
    Notification::fake();

    $user = TenantUser::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification): true {
        $response = $this->get(route('password.reset', ['token' => $notification->token]));

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function (): void {
    Notification::fake();

    $user = TenantUser::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user): true {
        $response = $this->post(route('password.store'), [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});

describe('password reset link validation', function (): void {
    test('password reset requires valid email format', function (): void {
        post(route('password.email'), ['email' => 'invalid-email'])
            ->assertSessionHasErrors(['email']);
    });

    test('password reset requires email field', function (): void {
        post(route('password.email'), [])
            ->assertSessionHasErrors(['email']);
    });

    test('password reset shows success message even for non-existent email', function (): void {
        $response = post(route('password.email'), ['email' => 'nonexistent@example.com']);

        $response->assertRedirect()
            ->assertSessionHas('status', 'A reset link will be sent if the account exists.');
    });
});

describe('new password validation', function (): void {
    test('password reset requires token', function (): void {
        post(route('password.store'), [
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ])
            ->assertSessionHasErrors(['token']);
    });

    test('password reset requires email', function (): void {
        post(route('password.store'), [
            'token' => 'valid-token',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ])
            ->assertSessionHasErrors(['email']);
    });

    test('password reset requires password', function (): void {
        post(route('password.store'), [
            'token' => 'valid-token',
            'email' => 'test@example.com',
            'password_confirmation' => 'newpassword',
        ])
            ->assertSessionHasErrors(['password']);
    });

    test('password reset requires password confirmation', function (): void {
        post(route('password.store'), [
            'token' => 'valid-token',
            'email' => 'test@example.com',
            'password' => 'newpassword',
        ])
            ->assertSessionHasErrors(['password']);
    });

    test('password reset fails with mismatched password confirmation', function (): void {
        post(route('password.store'), [
            'token' => 'valid-token',
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'differentpassword',
        ])
            ->assertSessionHasErrors(['password']);
    });

    test('password reset fails with invalid token', function (): void {
        $user = TenantUser::factory()->create();

        post(route('password.store'), [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ])
            ->assertSessionHasErrors(['email']);
    });

    test('password reset fails with non-existent email', function (): void {
        post(route('password.store'), [
            'token' => 'valid-token',
            'email' => 'nonexistent@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ])
            ->assertSessionHasErrors(['email']);
    });
});

describe('reset password screen rendering', function (): void {
    test('reset password screen displays email and token', function (): void {
        $response = get(route('password.reset', ['token' => 'test-token']).'?email=test@example.com');

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableInertia => $page
                    ->component('auth/reset-password')
                    ->has('email')
                    ->has('token')
            );
    });
});
