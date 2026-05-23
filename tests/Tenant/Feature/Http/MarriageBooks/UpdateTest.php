<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\MarriageCertificate;

use function Pest\Laravel\from;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE, TenantPermission::BOOKS_UPDATE);
    });

    it('can be updated', function (): void {
        $marriageCertificate = MarriageCertificate::factory()->create([
            'groom_name' => 'Juan Pérez',
        ]);

        from(route('books.marriage.index'))
            ->put(route('books.marriage.update', ['marriageCertificate' => $marriageCertificate]), updateMarriageCertificatePayload([
                'record_number' => '99',
                'groom_name' => 'Juan Pérez Actualizado',
            ]))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.marriage.index'));

        $marriageCertificate->refresh();

        expect($marriageCertificate->record_number)->toBe('99')
            ->and($marriageCertificate->groom_name)->toBe('Juan Pérez Actualizado');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be updated', function (): void {
        $marriageCertificate = MarriageCertificate::factory()->create([
            'groom_name' => 'Juan Pérez',
        ]);

        from(route('books.marriage.index'))
            ->put(route('books.marriage.update', ['marriageCertificate' => $marriageCertificate]), updateMarriageCertificatePayload([
                'groom_name' => 'Juan Pérez Actualizado',
            ]))
            ->assertForbidden();

        $marriageCertificate->refresh();

        expect($marriageCertificate->groom_name)->toBe('Juan Pérez');
    });
});

function updateMarriageCertificatePayload(array $overrides = []): array
{
    return [
        'book' => '1',
        'folio' => '10',
        'record_number' => '25',
        'married_at' => '2026-05-22',
        'priest' => 'Padre Juan',
        'groom_name' => 'Juan Pérez',
        'groom_age' => '30',
        'groom_birthplace' => 'San Juan',
        'groom_residence' => 'Ponce',
        'groom_father_name' => 'Carlos Pérez',
        'groom_mother_name' => 'Rosa Díaz',
        'bride_name' => 'María López',
        'bride_age' => '28',
        'bride_birthplace' => 'Bayamón',
        'bride_residence' => 'Ponce',
        'bride_father_name' => 'Luis López',
        'bride_mother_name' => 'Ana Torres',
        'witness1_name' => 'Pedro Soto',
        'witness2_name' => 'Carmen Cruz',
        'issued_at' => '2026-05-22',
        'marginal_note' => null,
        ...$overrides,
    ];
}
