<?php

declare(strict_types=1);

use App\Models\TenantUser;

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
