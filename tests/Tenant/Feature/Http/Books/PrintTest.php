<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\BaptismCertificate;
use App\Models\Church;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

it('can print a baptism certificate when user has permission', function (): void {
    $baptismCertificate = BaptismCertificate::factory()->create();

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.pdf', ['baptismCertificate' => $baptismCertificate]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

it('cannot print a baptism certificate when user does not have permission', function (): void {
    $baptismCertificate = BaptismCertificate::factory()->create();

    asUserWithoutPermission()
        ->get(route('books.pdf', ['baptismCertificate' => $baptismCertificate]))
        ->assertForbidden();
});
