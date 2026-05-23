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
        from(route('books.negativa.index'))
            ->post(route('books.negativa.store'), negativaPayload())
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.negativa.index'));

        assertDatabaseCount('negativas', 1);
        assertDatabaseHas('negativas', [
            'person_name' => 'Pedro Martínez',
        ]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be stored', function (): void {
        from(route('books.negativa.index'))
            ->post(route('books.negativa.store'), negativaPayload())
            ->assertForbidden();

        assertDatabaseCount('negativas', 0);
    });
});

function negativaPayload(array $overrides = []): array
{
    return [
        'person_name' => 'Pedro Martínez',
        'father_name' => 'Carlos Martínez',
        'mother_name' => 'Rosa Díaz',
        'searched_from' => '2020-01-01',
        'searched_to' => '2026-05-22',
        'priest' => 'Padre Juan',
        'issued_at' => '2026-05-22',
        ...$overrides,
    ];
}
