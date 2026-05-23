<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\MarriageCertificate;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

it('can print a marriage certificate when user has permission', function (): void {
    $marriageCertificate = MarriageCertificate::factory()->create();

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.marriage.pdf', ['marriageCertificate' => $marriageCertificate]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

it('cannot print a marriage certificate when user does not have permission', function (): void {
    $marriageCertificate = MarriageCertificate::factory()->create();

    asUserWithoutPermission()
        ->get(route('books.marriage.pdf', ['marriageCertificate' => $marriageCertificate]))
        ->assertForbidden();
});
