<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\ConfirmationCertificate;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\from;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE, TenantPermission::BOOKS_CREATE);
    });

    it('can be stored', function (): void {
        from(route('books.confirmation.index'))
            ->post(route('books.confirmation.store'), confirmationCertificatePayload())
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.confirmation.index'));

        assertDatabaseCount('confirmation_certificates', 1);
        assertDatabaseHas('confirmation_certificates', [
            'book' => '1',
            'folio' => '10',
            'record_number' => '25',
            'confirmed_name' => 'Maria Gonzalez',
        ]);
    });

    it('requires a unique book, folio, and number combination', function (): void {
        ConfirmationCertificate::factory()->create([
            'book' => '1',
            'folio' => '10',
            'record_number' => '25',
        ]);

        from(route('books.confirmation.index'))
            ->post(route('books.confirmation.store'), confirmationCertificatePayload())
            ->assertSessionHasErrors('record_number');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be stored', function (): void {
        from(route('books.confirmation.index'))
            ->post(route('books.confirmation.store'), confirmationCertificatePayload())
            ->assertForbidden();

        assertDatabaseCount('confirmation_certificates', 0);
    });
});

function confirmationCertificatePayload(array $overrides = []): array
{
    return [
        'book' => '1',
        'folio' => '10',
        'record_number' => '25',
        'priest' => 'Padre Juan',
        'confirmed_name' => 'Maria Gonzalez',
        'father_name' => 'Carlos Gonzalez',
        'mother_name' => 'Rosa Diaz',
        'confirmed_by' => 'Obispo Pedro',
        'confirmed_at' => '2026-05-22',
        'godfather_name' => 'Luis Torres',
        'godmother_name' => 'Ana Reyes',
        'issued_place' => 'Puerto Rico',
        'issued_at' => '2026-05-22',
        'marginal_note' => null,
        ...$overrides,
    ];
}
