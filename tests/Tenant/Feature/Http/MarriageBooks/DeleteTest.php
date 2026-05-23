<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\MarriageCertificate;

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
        $marriageCertificate = MarriageCertificate::factory()->create();

        from(route('books.marriage.index'))
            ->delete(route('books.marriage.destroy', ['marriageCertificate' => $marriageCertificate]))
            ->assertRedirect(route('books.marriage.index'));

        assertDatabaseCount('marriage_certificates', 0);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be deleted', function (): void {
        $marriageCertificate = MarriageCertificate::factory()->create();

        from(route('books.marriage.index'))
            ->delete(route('books.marriage.destroy', ['marriageCertificate' => $marriageCertificate]))
            ->assertForbidden();

        assertDatabaseCount('marriage_certificates', 1);
    });
});
