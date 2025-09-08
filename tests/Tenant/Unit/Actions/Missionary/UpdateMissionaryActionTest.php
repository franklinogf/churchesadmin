<?php

declare(strict_types=1);

use App\Actions\Missionary\UpdateMissionaryAction;
use App\Models\Missionary;

it('can update missionary basic data', function (): void {
    $missionary = Missionary::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'church' => 'South America Mission',
    ]);

    $updateData = [
        'name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'church' => 'New Mission Org',
    ];

    $action = new UpdateMissionaryAction();
    $action->handle($missionary, $updateData);

    $missionary->refresh();

    expect($missionary->name)->toBe('Jane')
        ->and($missionary->last_name)->toBe('Smith')
        ->and($missionary->email)->toBe('jane.smith@example.com')
        ->and($missionary->church)->toBe('New Mission Org');
});

it('can create address when missionary has none', function (): void {
    $missionary = Missionary::factory()->create();

    $updateData = [];
    $addressData = [
        'address_1' => '123 New Mission St',
        'city' => 'New Mission City',
        'state' => 'NM',
        'zip_code' => '10001',
        'country' => 'US',
    ];

    $action = new UpdateMissionaryAction();
    $action->handle($missionary, $updateData, $addressData);

    $missionary->refresh();

    expect($missionary->address)->not->toBeNull()
        ->and($missionary->address->address_1)->toBe('123 New Mission St')
        ->and($missionary->address->city)->toBe('New Mission City');
});

it('can update existing address', function (): void {
    $missionary = Missionary::factory()->hasAddress()->create();
    $originalAddress = $missionary->address->address_1;

    $updateData = [];
    $addressData = [
        'address_1' => '456 Updated Mission Ave',
        'city' => 'Updated Mission City',
    ];

    $action = new UpdateMissionaryAction();
    $action->handle($missionary, $updateData, $addressData);

    $missionary->refresh();

    expect($missionary->address->address_1)->toBe('456 Updated Mission Ave')
        ->and($missionary->address->address_1)->not->toBe($originalAddress)
        ->and($missionary->address->city)->toBe('Updated Mission City');
});

it('can delete address when set to null', function (): void {
    $missionary = Missionary::factory()->hasAddress()->create();

    expect($missionary->address)->not->toBeNull();

    $updateData = [];

    $action = new UpdateMissionaryAction();
    $action->handle($missionary, $updateData, null);

    $missionary->refresh();

    expect($missionary->address)->toBeNull();
});

it('can update all data at once', function (): void {
    $missionary = Missionary::factory()->hasAddress()->create([
        'name' => 'Old Name',
        'church' => 'Old Church',
    ]);

    $updateData = [
        'name' => 'Updated Name',
        'church' => 'Updated Mission',
        'phone' => '+555-9999',
    ];
    $addressData = [
        'address_1' => '789 Complete Mission St',
        'city' => 'Complete Mission City',
    ];

    $action = new UpdateMissionaryAction();
    $action->handle($missionary, $updateData, $addressData);

    $missionary->refresh();

    expect($missionary->name)->toBe('Updated Name')
        ->and($missionary->church)->toBe('Updated Mission')
        ->and($missionary->phone)->toBe('+555-9999')
        ->and($missionary->address->address_1)->toBe('789 Complete Mission St');
});
