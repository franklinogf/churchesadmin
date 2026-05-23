<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\FirstCommunionCertificate;

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
        $firstCommunionCertificate = FirstCommunionCertificate::factory()->create();

        from(route('books.communion.index'))
            ->delete(route('books.communion.destroy', ['firstCommunionCertificate' => $firstCommunionCertificate]))
            ->assertRedirect(route('books.communion.index'));

        assertDatabaseCount('first_communion_certificates', 0);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be deleted', function (): void {
        $firstCommunionCertificate = FirstCommunionCertificate::factory()->create();

        from(route('books.communion.index'))
            ->delete(route('books.communion.destroy', ['firstCommunionCertificate' => $firstCommunionCertificate]))
            ->assertForbidden();

        assertDatabaseCount('first_communion_certificates', 1);
    });
});
