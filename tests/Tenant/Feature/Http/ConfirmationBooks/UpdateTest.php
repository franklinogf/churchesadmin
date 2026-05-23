<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\ConfirmationCertificate;

use function Pest\Laravel\from;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE, TenantPermission::BOOKS_UPDATE);
    });

    it('can be updated', function (): void {
        $confirmationCertificate = ConfirmationCertificate::factory()->create([
            'confirmed_name' => 'Maria Gonzalez',
        ]);

        from(route('books.confirmation.index'))
            ->put(route('books.confirmation.update', ['confirmationCertificate' => $confirmationCertificate]), updateConfirmationCertificatePayload([
                'record_number' => '99',
                'confirmed_name' => 'Maria Gonzalez Actualizada',
            ]))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.confirmation.index'));

        $confirmationCertificate->refresh();

        expect($confirmationCertificate->record_number)->toBe('99')
            ->and($confirmationCertificate->confirmed_name)->toBe('Maria Gonzalez Actualizada');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be updated', function (): void {
        $confirmationCertificate = ConfirmationCertificate::factory()->create([
            'confirmed_name' => 'Maria Gonzalez',
        ]);

        from(route('books.confirmation.index'))
            ->put(route('books.confirmation.update', ['confirmationCertificate' => $confirmationCertificate]), updateConfirmationCertificatePayload([
                'record_number' => '99',
                'confirmed_name' => 'Maria Gonzalez Actualizada',
            ]))
            ->assertForbidden();

        $confirmationCertificate->refresh();

        expect($confirmationCertificate->confirmed_name)->toBe('Maria Gonzalez');
    });
});

function updateConfirmationCertificatePayload(array $overrides = []): array
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
