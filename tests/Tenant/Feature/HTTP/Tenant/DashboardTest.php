<?php

declare(strict_types=1);

use App\Models\TenantUser;

test('guests are redirected to the login page', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function (): void {
    $this->actingAs(TenantUser::factory()->create());

    $this->get(route('dashboard'))->assertOk();
});
