<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\ConfirmationCertificate;

beforeEach(function (): void {
    Church::current()?->update(['features' => ['books' => true]]);
});

it('can print a confirmation certificate when user has permission', function (): void {
    $confirmationCertificate = ConfirmationCertificate::factory()->create();

    asUserWithPermission(TenantPermission::BOOKS_MANAGE)
        ->get(route('books.confirmation.pdf', ['confirmationCertificate' => $confirmationCertificate]))
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/pdf');
});

it('cannot print a confirmation certificate when user does not have permission', function (): void {
    $confirmationCertificate = ConfirmationCertificate::factory()->create();

    asUserWithoutPermission()
        ->get(route('books.confirmation.pdf', ['confirmationCertificate' => $confirmationCertificate]))
        ->assertForbidden();
});
