<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\BaptismCertificate;
use App\Models\Church;

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
        $baptismCertificate = BaptismCertificate::factory()->create();

        from(route('books.baptism.index'))
            ->delete(route('books.baptism.destroy', ['baptismCertificate' => $baptismCertificate]))
            ->assertRedirect(route('books.baptism.index'));

        assertDatabaseCount('baptism_certificates', 0);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be deleted', function (): void {
        $baptismCertificate = BaptismCertificate::factory()->create();

        from(route('books.baptism.index'))
            ->delete(route('books.baptism.destroy', ['baptismCertificate' => $baptismCertificate]))
            ->assertForbidden();

        assertDatabaseCount('baptism_certificates', 1);
    });
});
