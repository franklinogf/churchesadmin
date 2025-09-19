<?php

declare(strict_types=1);

namespace Tests\Feature\HTTP\Tenant\Visit\Actions;

use App\Actions\Visit\CreateVisitAction;
use App\Models\Visit;

it('can create a visit without an address', function (): void {

    $visitData = [
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'phone' => '+123456789',
        'first_visit_date' => '2025-05-01',
    ];

    $action = new CreateVisitAction();
    $visit = $action->handle($visitData);

    expect($visit)->toBeInstanceOf(Visit::class)
        ->and($visit->name)->toBe('John')
        ->and($visit->last_name)->toBe('Doe')
        ->and($visit->email)->toBe('john.doe@example.com')
        ->and($visit->phone)->toBe('+123456789')
        ->and($visit->first_visit_date->toDateString())->toBe('2025-05-01')
        ->and($visit->address)->toBeNull();
});

it('can create a visit without phone number', function (): void {

    $visitData = [
        'name' => 'Alice',
        'last_name' => 'Johnson',
        'email' => 'alice.johnson@example.com',
        'phone' => null,
        'first_visit_date' => '2025-05-01',
    ];

    $action = new CreateVisitAction();
    $visit = $action->handle($visitData);

    expect($visit)->toBeInstanceOf(Visit::class)
        ->and($visit->name)->toBe('Alice')
        ->and($visit->last_name)->toBe('Johnson')
        ->and($visit->email)->toBe('alice.johnson@example.com')
        ->and($visit->phone)->toBeNull()
        ->and($visit->first_visit_date->toDateString())->toBe('2025-05-01')
        ->and($visit->address)->toBeNull();
});

it('can create a visit with an address', function (): void {

    $visitData = [
        'name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'phone' => '+987654321',
        'first_visit_date' => '2025-05-02',
    ];

    $addressData = [
        'address_1' => '123 Main St',
        'address_2' => 'Apt 4B',
        'city' => 'Anytown',
        'state' => 'CA',
        'zip_code' => '12345',
        'country' => 'US',
    ];

    $action = new CreateVisitAction();
    $visit = $action->handle($visitData, $addressData);

    expect($visit)->toBeInstanceOf(Visit::class)
        ->and($visit->name)->toBe('Jane')
        ->and($visit->last_name)->toBe('Smith');

    expect($visit->address)->not->toBeNull()
        ->and($visit->address->address_1)->toBe('123 Main St')
        ->and($visit->address->address_2)->toBe('Apt 4B')
        ->and($visit->address->city)->toBe('Anytown')
        ->and($visit->address->state)->toBe('CA')
        ->and($visit->address->zip_code)->toBe('12345')
        ->and($visit->address->country)->toBe('US');
});
