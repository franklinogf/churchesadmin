<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\MarriageCertificate;

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
        from(route('books.marriage.index'))
            ->post(route('books.marriage.store'), marriageCertificatePayload())
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.marriage.index'));

        assertDatabaseCount('marriage_certificates', 1);
        assertDatabaseHas('marriage_certificates', [
            'book' => '1',
            'folio' => '10',
            'record_number' => '25',
            'groom_name' => 'Juan Pérez',
            'bride_name' => 'María López',
        ]);
    });

    it('requires a unique book, folio, and number combination', function (): void {
        MarriageCertificate::factory()->create([
            'book' => '1',
            'folio' => '10',
            'record_number' => '25',
        ]);

        from(route('books.marriage.index'))
            ->post(route('books.marriage.store'), marriageCertificatePayload())
            ->assertSessionHasErrors('record_number');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be stored', function (): void {
        from(route('books.marriage.index'))
            ->post(route('books.marriage.store'), marriageCertificatePayload())
            ->assertForbidden();

        assertDatabaseCount('marriage_certificates', 0);
    });
});

function marriageCertificatePayload(array $overrides = []): array
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
