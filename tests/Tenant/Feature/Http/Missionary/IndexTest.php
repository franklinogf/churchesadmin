<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Missionary;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('can be rendered if authenticated user has permission', function (): void {
    Missionary::factory()->count(3)->create();

    asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE)
        ->get(route('missionaries.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('main/missionaries/index')
            ->has('missionaries', 3)
        );
});

it('cannot be rendered if not authenticated', function (): void {
    get(route('missionaries.index'))
        ->assertRedirect(route('login'));
});

it('only shows not trashed missionaries', function (): void {
    Missionary::factory()->count(3)->create();
    Missionary::factory()->count(2)->trashed()->create();

    asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE)
        ->get(route('missionaries.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('main/missionaries/index')
            ->has('missionaries', 3)
        );
});

it('cannot be rendered if authenticated user does not have permission', function (): void {
    asUserWithoutPermission()
        ->get(route('missionaries.index'))
        ->assertForbidden();
});
