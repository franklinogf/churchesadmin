<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\Negativa;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

it('can print a negativa when user has permission', function (): void {
    $negativa = Negativa::factory()->create();

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.negativa.pdf', ['negativa' => $negativa]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

it('cannot print a negativa when user does not have permission', function (): void {
    $negativa = Negativa::factory()->create();

    asUserWithoutPermission()
        ->get(route('books.negativa.pdf', ['negativa' => $negativa]))
        ->assertForbidden();
});
