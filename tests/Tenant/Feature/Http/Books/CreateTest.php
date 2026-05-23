<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\BaptismCertificate;
use App\Models\Church;

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
        from(route('books.baptism.index'))
            ->post(route('books.baptism.store'), baptismCertificatePayload())
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.baptism.index'));

        assertDatabaseCount('baptism_certificates', 1);
        assertDatabaseHas('baptism_certificates', [
            'book' => '1',
            'folio' => '10',
            'record_number' => '25',
            'baptized_name' => 'Juan Perez',
        ]);
    });

    it('requires a unique book, folio, and number combination', function (): void {
        BaptismCertificate::factory()->create([
            'book' => '1',
            'folio' => '10',
            'record_number' => '25',
        ]);

        from(route('books.baptism.index'))
            ->post(route('books.baptism.store'), baptismCertificatePayload())
            ->assertSessionHasErrors('record_number');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be stored', function (): void {
        from(route('books.baptism.index'))
            ->post(route('books.baptism.store'), baptismCertificatePayload())
            ->assertForbidden();

        assertDatabaseCount('baptism_certificates', 0);
    });
});

function baptismCertificatePayload(array $overrides = []): array
{
    return [
        'book' => '1',
        'folio' => '10',
        'record_number' => '25',
        'baptized_name' => 'Juan Perez',
        'baptized_at' => '2026-05-21',
        'priest' => 'Padre Jose',
        'birth_place' => 'San Juan',
        'birth_date' => '2026-01-01',
        'father_name' => 'Pedro Perez',
        'father_origin_place' => 'Ponce',
        'father_residence_place' => 'San Juan',
        'mother_name' => 'Maria Rivera',
        'mother_origin_place' => 'Mayaguez',
        'mother_residence_place' => 'San Juan',
        'paternal_grandfather_name' => 'Luis Perez',
        'paternal_grandmother_name' => 'Ana Lopez',
        'maternal_grandfather_name' => 'Carlos Rivera',
        'maternal_grandmother_name' => 'Rosa Diaz',
        'godfather_name' => 'Miguel Soto',
        'godmother_name' => 'Elena Cruz',
        'issued_place' => 'Puerto Rico',
        'issued_at' => '2026-05-21',
        'marginal_note' => 'Nota de prueba',
        ...$overrides,
    ];
}
