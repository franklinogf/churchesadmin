<?php

declare(strict_types=1);

use App\Actions\Missionary\CreateMissionaryAction;
use App\Models\Missionary;

it('can create a missionary with basic data', function (): void {
    $missionaryData = [
        'name' => 'John',
        'last_name' => 'Missionary',
        'email' => 'john.missionary@example.com',
        'phone' => '+123456789',
        'gender' => 'male',
        'church' => 'Global Mission Org',
    ];

    $action = new CreateMissionaryAction();
    $action->handle($missionaryData);

    $missionary = Missionary::where('email', 'john.missionary@example.com')->first();

    expect($missionary)->not->toBeNull()
        ->and($missionary->name)->toBe('John')
        ->and($missionary->last_name)->toBe('Missionary')
        ->and($missionary->email)->toBe('john.missionary@example.com')
        ->and($missionary->phone)->toBe('+123456789')
        ->and($missionary->church)->toBe('Global Mission Org');
});

it('can create a missionary with address', function (): void {
    $missionaryData = [
        'name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'gender' => 'female',
        'church' => 'Africa Mission',
    ];

    $addressData = [
        'address_1' => '123 Mission St',
        'address_2' => 'Apt 2B',
        'city' => 'Mission City',
        'state' => 'MC',
        'zip_code' => '54321',
        'country' => 'US',
    ];

    $action = new CreateMissionaryAction();
    $action->handle($missionaryData, $addressData);

    $missionary = Missionary::where('email', 'jane.smith@example.com')->first();

    expect($missionary)->not->toBeNull()
        ->and($missionary->address)->not->toBeNull()
        ->and($missionary->address->address_1)->toBe('123 Mission St')
        ->and($missionary->address->address_2)->toBe('Apt 2B')
        ->and($missionary->address->city)->toBe('Mission City')
        ->and($missionary->address->state)->toBe('MC')
        ->and($missionary->address->zip_code)->toBe('54321')
        ->and($missionary->address->country)->toBe('US');
});

it('can create a missionary without address', function (): void {
    $missionaryData = [
        'name' => 'Bob',
        'last_name' => 'Wilson',
        'email' => 'bob.wilson@example.com',
        'gender' => 'male',
        'church' => 'Asia Mission',
    ];

    $action = new CreateMissionaryAction();
    $action->handle($missionaryData);

    $missionary = Missionary::where('email', 'bob.wilson@example.com')->first();

    expect($missionary)->not->toBeNull()
        ->and($missionary->address)->toBeNull()
        ->and($missionary->church)->toBe('Asia Mission');
});
