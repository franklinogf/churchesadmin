<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
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
        from(route('books.communion.index'))
            ->post(route('books.communion.store'), communionCertificatePayload())
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.communion.index'));

        assertDatabaseCount('first_communion_certificates', 1);
        assertDatabaseHas('first_communion_certificates', [
            'communicant_name' => 'Ana García',
            'priest' => 'Padre Juan',
        ]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be stored', function (): void {
        from(route('books.communion.index'))
            ->post(route('books.communion.store'), communionCertificatePayload())
            ->assertForbidden();

        assertDatabaseCount('first_communion_certificates', 0);
    });
});

function communionCertificatePayload(array $overrides = []): array
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
