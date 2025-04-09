<?php

declare(strict_types=1);

use App\Enums\TenantPermissionName;
use App\Models\Tag;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

test('index page can not be rendered if not authenticated', function (): void {
    get(route('categories.index'))
        ->assertRedirect(route('login'));
});

describe('user has permission', function (): void {
    test('index page can be rendered if authenticated', function (): void {
        Tag::factory(10)->category()->create();
        asUserWithPermission(TenantPermissionName::MANAGE_CATEGORIES)->get(route('categories.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('categories/index')
                ->has('categories', 10)
            );
    });

});

describe('user does not have permission', function (): void {

    test('index page can not be rendered', function (): void {

        asUserWithoutPermission()->get(route('categories.index'))
            ->assertNotFound();
    });

});
