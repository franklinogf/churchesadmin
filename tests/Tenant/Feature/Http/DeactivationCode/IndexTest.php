<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\DeactivationCode;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('codes.deactivationCodes.index'))
        ->assertRedirect(route('login'));
});

it('can be rendered if authenticated user has permission', function (): void {
    DeactivationCode::factory(3)->create();

    asUserWithPermission(TenantPermission::DEACTIVATION_CODES_MANAGE)
        ->get(route('codes.deactivationCodes.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('codes/deactivationCodes/index')
            ->has('deactivationCodes', 3)
        );
});

it('cannot be rendered if authenticated user does not have permission', function (): void {
    asUserWithoutPermission()
        ->get(route('codes.deactivationCodes.index'))
        ->assertForbidden();
});
