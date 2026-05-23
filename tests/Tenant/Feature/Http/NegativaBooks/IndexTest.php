<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\Negativa;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

it('cannot be rendered if not authenticated', function (): void {
    get(route('books.negativa.index'))
        ->assertRedirect(route('login'));
});

it('can be rendered if authenticated user has permission and books feature is enabled', function (): void {
    Negativa::factory(3)->create();

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.negativa.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('main/books/negativa/index')
            ->has('negativas', 3)
        );
});

it('cannot be rendered if authenticated user does not have permission', function (): void {
    asUserWithoutPermission()
        ->get(route('books.negativa.index'))
        ->assertForbidden();
});

it('cannot be rendered if books feature is disabled', function (): void {
    Church::current()?->update(['features' => ['books' => false]]);

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.negativa.index'))
        ->assertForbidden();
});
