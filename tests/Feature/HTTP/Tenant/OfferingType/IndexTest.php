<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\OfferingType;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('codes.offeringTypes.index'))
        ->assertRedirect(route('login'));
});

it('can be rendered if authenticated user has permission', function (): void {
    OfferingType::factory(3)->create();

    asUserWithPermission(TenantPermission::OFFERING_TYPES_MANAGE)
        ->get(route('codes.offeringTypes.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('codes/offeringTypes/index')
            ->has('offeringTypes', 3)
        );
});

it('cannot be rendered if authenticated user does not have permission', function (): void {
    asUserWithoutPermission()
        ->get(route('codes.offeringTypes.index'))
        ->assertForbidden();
});
