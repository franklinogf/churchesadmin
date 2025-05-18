<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Member;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('can be rendered if authenticated user has permission', function (): void {
    Member::factory()->count(3)->create();

    asUserWithPermission(TenantPermission::MANAGE_MEMBERS)
        ->get(route('members.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('members/index')
            ->has('members', 3)
        );
});

it('cannot be rendered if not authenticated', function (): void {
    get(route('members.index'))
        ->assertRedirect(route('login'));
});

it('only shows not trashed members', function (): void {
    Member::factory()->count(3)->create();
    Member::factory()->count(2)->trashed()->create();

    asUserWithPermission(TenantPermission::MANAGE_MEMBERS)
        ->get(route('members.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('members/index')
            ->has('members', 3)
        );
});

it('cannot be rendered if authenticated user does not have permission', function (): void {
    asUserWithoutPermission()
        ->get(route('members.index'))
        ->assertForbidden();
});
