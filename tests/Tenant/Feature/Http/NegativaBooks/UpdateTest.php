<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\Negativa;

use function Pest\Laravel\from;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE, TenantPermission::BOOKS_UPDATE);
    });

    it('can be updated', function (): void {
        $negativa = Negativa::factory()->create([
            'person_name' => 'Pedro Martínez',
        ]);

        from(route('books.negativa.index'))
            ->put(route('books.negativa.update', ['negativa' => $negativa]), updateNegativaPayload([
                'person_name' => 'Pedro Martínez Actualizado',
            ]))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('books.negativa.index'));

        $negativa->refresh();

        expect($negativa->person_name)->toBe('Pedro Martínez Actualizado');
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::BOOKS_MANAGE);
    });

    it('cannot be updated', function (): void {
        $negativa = Negativa::factory()->create([
            'person_name' => 'Pedro Martínez',
        ]);

        from(route('books.negativa.index'))
            ->put(route('books.negativa.update', ['negativa' => $negativa]), updateNegativaPayload([
                'person_name' => 'Pedro Martínez Actualizado',
            ]))
            ->assertForbidden();

        $negativa->refresh();

        expect($negativa->person_name)->toBe('Pedro Martínez');
    });
});

function updateNegativaPayload(array $overrides = []): array
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
