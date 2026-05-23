<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\Negativa;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\from;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE, TenantPermission::BOOKS_DELETE);
    });

    it('can be deleted', function (): void {
        $negativa = Negativa::factory()->create();

        from(route('books.negativa.index'))
            ->delete(route('books.negativa.destroy', ['negativa' => $negativa]))
            ->assertRedirect(route('books.negativa.index'));

        assertDatabaseCount('negativas', 0);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be deleted', function (): void {
        $negativa = Negativa::factory()->create();

        from(route('books.negativa.index'))
            ->delete(route('books.negativa.destroy', ['negativa' => $negativa]))
            ->assertForbidden();

        assertDatabaseCount('negativas', 1);
    });
});
