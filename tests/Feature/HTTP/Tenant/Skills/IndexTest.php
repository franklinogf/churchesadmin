<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Tag;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {

    get(route('skills.index'))
        ->assertRedirect(route('login'));
});

it('can be rendered if authenticated user has permission', function (): void {
    Tag::factory(10)->skill()->create();

    asUserWithPermission(TenantPermission::MANAGE_SKILLS)
        ->get(route('skills.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('skills/index')
            ->has('skills', 10)
        );
});

it('cannot be rendered if authenticated user does not have permission', function (): void {

    asUserWithoutPermission()
        ->get(route('skills.index'))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas(FlashMessageKey::ERROR->value);
});
