<?php

declare(strict_types=1);

use App\Models\TenantUser;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

describe('email verification notification endpoint', function (): void {
    test('verified user is redirected to dashboard', function (): void {
        $user = TenantUser::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = actingAs($user, 'tenant')->post(route('verification.send'));

        $response->assertRedirect(route('dashboard', absolute: false));
    });

    test('unverified user can send email verification notification', function (): void {
        Notification::fake();

        $user = TenantUser::factory()->unverified()->create();

        $response = actingAs($user, 'tenant')->post(route('verification.send'));

        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertRedirect()
            ->assertSessionHas('status', 'verification-link-sent');
    });

    test('guest cannot send email verification notification', function (): void {
        $response = post(route('verification.send'));

        $response->assertRedirect(route('login'));
    });
});
