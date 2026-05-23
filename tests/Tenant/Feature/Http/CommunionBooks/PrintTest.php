<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\FirstCommunionCertificate;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

it('can print a first communion certificate when user has permission', function (): void {
    $firstCommunionCertificate = FirstCommunionCertificate::factory()->create();

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.communion.pdf', ['firstCommunionCertificate' => $firstCommunionCertificate]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

it('cannot print a first communion certificate when user does not have permission', function (): void {
    $firstCommunionCertificate = FirstCommunionCertificate::factory()->create();

    asUserWithoutPermission()
        ->get(route('books.communion.pdf', ['firstCommunionCertificate' => $firstCommunionCertificate]))
        ->assertForbidden();
});
