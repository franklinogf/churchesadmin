<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\FirstCommunionCertificate;

use function Pest\Laravel\from;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE, TenantPermission::BOOKS_UPDATE);
    });

    it('can be updated', function (): void {
        $firstCommunionCertificate = FirstCommunionCertificate::factory()->create([
            'communicant_name' => 'Ana García',
        ]);

        from(route('books.communion.index'))
            ->put(route('books.communion.update', ['firstCommunionCertificate' => $firstCommunionCertificate]), updateCommunionCertificatePayload([
                'communicant_name' => 'Ana García Actualizada',
            ]))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.communion.index'));

        $firstCommunionCertificate->refresh();

        expect($firstCommunionCertificate->communicant_name)->toBe('Ana García Actualizada');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be updated', function (): void {
        $firstCommunionCertificate = FirstCommunionCertificate::factory()->create([
            'communicant_name' => 'Ana García',
        ]);

        from(route('books.communion.index'))
            ->put(route('books.communion.update', ['firstCommunionCertificate' => $firstCommunionCertificate]), updateCommunionCertificatePayload([
                'communicant_name' => 'Ana García Actualizada',
            ]))
            ->assertForbidden();

        $firstCommunionCertificate->refresh();

        expect($firstCommunionCertificate->communicant_name)->toBe('Ana García');
    });
});

function updateCommunionCertificatePayload(array $overrides = []): array
{
    return [
        'priest' => 'Padre Juan',
        'communicant_name' => 'Ana García',
        'father_name' => 'Carlos García',
        'mother_name' => 'Rosa Díaz',
        'communion_at' => '2026-05-22',
        'issued_at' => '2026-05-22',
        ...$overrides,
    ];
}
