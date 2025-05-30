<?php

declare(strict_types=1);

namespace Tests\Feature\HTTP\Tenant\Visit\Actions;

use App\Actions\Visit\UpdateVisitAction;
use App\Enums\TenantPermission;
use App\Models\Visit;

beforeEach(function (): void {
    $this->visit = Visit::factory()->create([
        'name' => 'Original',
        'last_name' => 'Name',
        'email' => 'original@example.com',
        'phone' => '+111222333',
        'first_visit_date' => '2025-04-01',
    ]);
});

it('can update a visit without changing address', function (): void {
    asUserWithPermission(TenantPermission::VISITS_UPDATE);

    $visitData = [
        'name' => 'Updated',
        'last_name' => 'User',
    ];

    $action = new UpdateVisitAction();
    $updatedVisit = $action->handle($this->visit, $visitData);

    expect($updatedVisit)->toBeInstanceOf(Visit::class)
        ->and($updatedVisit->name)->toBe('Updated')
        ->and($updatedVisit->last_name)->toBe('User')
        ->and($updatedVisit->email)->toBe('original@example.com')
        ->and($updatedVisit->phone)->toBe('+111222333')
        ->and($updatedVisit->first_visit_date->toDateString())->toBe('2025-04-01');
});

it('can update a visit with a new address', function (): void {
    asUserWithPermission(TenantPermission::VISITS_UPDATE);

    $visitData = [
        'name' => 'Address',
        'last_name' => 'Updated',
    ];

    $addressData = [
        'address_1' => '456 New Ave',
        'address_2' => 'Unit 7C',
        'city' => 'New City',
        'state' => 'NY',
        'zip_code' => '54321',
        'country' => 'US',
    ];

    $action = new UpdateVisitAction();
    $updatedVisit = $action->handle($this->visit, $visitData, $addressData);

    expect($updatedVisit)->toBeInstanceOf(Visit::class)
        ->and($updatedVisit->name)->toBe('Address')
        ->and($updatedVisit->last_name)->toBe('Updated');

    $updatedVisit->refresh();
    expect($updatedVisit->address)->not->toBeNull()
        ->and($updatedVisit->address->address_1)->toBe('456 New Ave')
        ->and($updatedVisit->address->address_2)->toBe('Unit 7C')
        ->and($updatedVisit->address->city)->toBe('New City');
});

it('can set address to null', function (): void {
    asUserWithPermission(TenantPermission::VISITS_UPDATE);

    // First create an address for the visit
    $this->visit->address()->create([
        'address_1' => 'Initial Address',
        'city' => 'Initial City',
        'state' => 'CA',
        'zip_code' => '12345',
        'country' => 'US',
    ]);

    $visitData = [
        'name' => 'No',
        'last_name' => 'Address',
    ];

    $action = new UpdateVisitAction();
    $updatedVisit = $action->handle($this->visit, $visitData, null);

    expect($updatedVisit)->toBeInstanceOf(Visit::class)
        ->and($updatedVisit->name)->toBe('No')
        ->and($updatedVisit->last_name)->toBe('Address');

    $updatedVisit->refresh();
    expect($updatedVisit->address)->toBeNull();
});
