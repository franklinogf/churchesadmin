<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\BaptismCertificate;
use App\Models\Church;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

it('cannot be rendered if not authenticated', function (): void {
    get(route('books.index'))
        ->assertRedirect(route('login'));
});

it('can be rendered if authenticated user has permission and books feature is enabled', function (): void {
    BaptismCertificate::factory(3)->create();

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('main/books/index')
            ->has('baptismCertificates', 3)
        );
});

it('cannot be rendered if authenticated user does not have permission', function (): void {
    asUserWithoutPermission()
        ->get(route('books.index'))
        ->assertForbidden();
});

it('cannot be rendered if books feature is disabled', function (): void {
    Church::current()?->update(['features' => ['books' => false]]);

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.index'))
        ->assertForbidden();
});
