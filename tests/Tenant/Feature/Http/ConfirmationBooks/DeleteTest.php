<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\ConfirmationCertificate;

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
        $confirmationCertificate = ConfirmationCertificate::factory()->create();

        from(route('books.confirmation.index'))
            ->delete(route('books.confirmation.destroy', ['confirmationCertificate' => $confirmationCertificate]))
            ->assertRedirect(route('books.confirmation.index'));

        assertDatabaseCount('confirmation_certificates', 0);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be deleted', function (): void {
        $confirmationCertificate = ConfirmationCertificate::factory()->create();

        from(route('books.confirmation.index'))
            ->delete(route('books.confirmation.destroy', ['confirmationCertificate' => $confirmationCertificate]))
            ->assertForbidden();

        assertDatabaseCount('confirmation_certificates', 1);
    });
});
