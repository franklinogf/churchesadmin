<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use App\Enums\TenantPermission;
use App\Models\Missionary;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\from;
use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('missionaries.create'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE, TenantPermission::MISSIONARIES_CREATE);
    });

    it('can be rendered if authenticated', function (): void {

        get(route('missionaries.create'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('missionaries/create')
                ->has('offeringFrequencies')
                ->has('genders')
            );

    });

    it('can be stored without an address', function (): void {

        from(route('missionaries.create'))
            ->post(route('missionaries.store'), [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+19293394305',
                'gender' => Gender::MALE->value,
                'church' => 'Church name',
                'offering' => '10.15',
                'offering_frequency' => OfferingFrequency::MONTHLY->value,
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('missionaries.index'));

        assertDatabaseCount('missionaries', 1);
        assertDatabaseCount('addresses', 0);

        $missionary = Missionary::latest()->first();

        expect($missionary)->not->toBeNull()
            ->and($missionary->name)->toBe('John')
            ->and($missionary->last_name)->toBe('Doe')
            ->and($missionary->email)->toBe('john.doe@example.com')
            ->and($missionary->phone)->toBe('+19293394305')
            ->and($missionary->gender)->toBe(Gender::MALE)
            ->and($missionary->church)->toBe('Church name')
            ->and($missionary->offering)->toBe('10.15')
            ->and($missionary->offering_frequency)->toBe(OfferingFrequency::MONTHLY)
            ->and($missionary->address)->toBeNull();
    });

    it('can be stored with an address', function (): void {

        from(route('missionaries.create'))
            ->post(route('missionaries.store'), [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+19293394305',
                'gender' => Gender::MALE->value,
                'church' => 'Church name',
                'offering' => '10.15',
                'offering_frequency' => OfferingFrequency::MONTHLY->value,
                'address' => [
                    'address_1' => '123 Main St',
                    'address_2' => 'Apt 4B',
                    'city' => 'Anytown',
                    'state' => 'CA',
                    'country' => 'US',
                    'zip_code' => '12345',
                ],
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('missionaries.index'));

        assertDatabaseCount('missionaries', 1);
        assertDatabaseCount('addresses', 1);

        $missionary = Missionary::latest()->first();

        expect($missionary)->not->toBeNull()
            ->and($missionary->name)->toBe('John')
            ->and($missionary->last_name)->toBe('Doe')
            ->and($missionary->email)->toBe('john.doe@example.com')
            ->and($missionary->phone)->toBe('+19293394305')
            ->and($missionary->gender)->toBe(Gender::MALE)
            ->and($missionary->church)->toBe('Church name')
            ->and($missionary->offering)->toBe('10.15')
            ->and($missionary->offering_frequency)->toBe(OfferingFrequency::MONTHLY)
            ->and($missionary->address)->not->toBeNull();
    });

});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE);
    });

    it('cannot be rendered if authenticated', function (): void {
        get(route('missionaries.create'))
            ->assertForbidden();
    });

    it('cannot be stored', function (): void {
        from(route('missionaries.create'))
            ->post(route('missionaries.store'), [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+19293394305',
                'gender' => Gender::MALE->value,
                'church' => 'Church name',
                'offering' => '10.15',
                'offering_frequency' => OfferingFrequency::MONTHLY->value,
                'address' => [
                    'address_1' => '123 Main St',
                    'address_2' => 'Apt 4B',
                    'city' => 'Anytown',
                    'state' => 'CA',
                    'country' => 'US',
                    'zip_code' => '12345',
                ],
            ])
            ->assertForbidden();
    });
});
