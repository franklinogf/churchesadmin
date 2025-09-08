<?php

declare(strict_types=1);

use App\Models\TenantUser;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function (): void {
    $user = TenantUser::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('password.edit'))
        ->put(route('password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('password.edit'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function (): void {
    $user = TenantUser::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->put(route('password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect(route('profile.edit'));
});
