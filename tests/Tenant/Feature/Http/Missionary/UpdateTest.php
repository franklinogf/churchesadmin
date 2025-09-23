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
    $missionary = Missionary::factory()->create();
    get(route('missionaries.edit', ['missionary' => $missionary]))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {

    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE, TenantPermission::MISSIONARIES_UPDATE);
    });

    it('can be rendered if authenticated', function (): void {
        $missionary = Missionary::factory()->create();
        get(route('missionaries.edit', ['missionary' => $missionary]))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('missionaries/edit')
                ->has('missionary')
                ->has('offeringFrequencies')
                ->has('genders')
            );
    });

    it('can be updated without an address', function (): void {
        $missionary = Missionary::factory()->create();

        from(route('missionaries.edit', ['missionary' => $missionary]))
            ->put(route('missionaries.update', ['missionary' => $missionary]), [
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

        assertDatabaseCount('addresses', 0);

        $updatedMissionary = Missionary::latest()->first();

        expect($updatedMissionary)->not->toBeNull()
            ->and($updatedMissionary->name)->toBe('John')
            ->and($updatedMissionary->last_name)->toBe('Doe')
            ->and($updatedMissionary->email)->toBe('john.doe@example.com')
            ->and($updatedMissionary->phone)->toBe('+19293394305')
            ->and($updatedMissionary->gender)->toBe(Gender::MALE)
            ->and($updatedMissionary->church)->toBe('Church name')
            ->and($updatedMissionary->offering)->toBe('10.15')
            ->and($updatedMissionary->offering_frequency)->toBe(OfferingFrequency::MONTHLY)
            ->and($updatedMissionary->address)->toBeNull();
    });

    it('can be updated without an address when it already has an address', function (): void {
        $missionary = Missionary::factory()->hasAddress()->create(
            [
                'name' => 'Nicole',
                'last_name' => 'Lopez',
                'email' => 'nicole.lopez@example.com',
                'phone' => '+19293390000',
                'gender' => Gender::FEMALE->value,
                'church' => 'Old Church',
                'offering' => '5.00',
                'offering_frequency' => OfferingFrequency::WEEKLY->value,
            ]
        );

        from(route('missionaries.edit', ['missionary' => $missionary]))
            ->put(route('missionaries.update', ['missionary' => $missionary]), [
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

        assertDatabaseCount('addresses', 0);

        $updatedMissionary = Missionary::latest()->first();

        expect($updatedMissionary)->not->toBeNull()
            ->and($updatedMissionary->name)->toBe('John')
            ->and($updatedMissionary->last_name)->toBe('Doe')
            ->and($updatedMissionary->email)->toBe('john.doe@example.com')
            ->and($updatedMissionary->phone)->toBe('+19293394305')
            ->and($updatedMissionary->gender)->toBe(Gender::MALE)
            ->and($updatedMissionary->church)->toBe('Church name')
            ->and($updatedMissionary->offering)->toBe('10.15')
            ->and($updatedMissionary->offering_frequency)->toBe(OfferingFrequency::MONTHLY)
            ->and($updatedMissionary->address)->toBeNull();
    });

    it('can be updated with an address when it already has an address', function (): void {
        $missionary = Missionary::factory()->hasAddress()->create();
        from(route('missionaries.edit', ['missionary' => $missionary]))
            ->put(route('missionaries.update', ['missionary' => $missionary]), [
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

        assertDatabaseCount('addresses', 1);

        $updatedMissionary = Missionary::latest()->first();

        expect($updatedMissionary)->not->toBeNull()
            ->and($updatedMissionary->name)->toBe('John')
            ->and($updatedMissionary->last_name)->toBe('Doe')
            ->and($updatedMissionary->email)->toBe('john.doe@example.com')
            ->and($updatedMissionary->phone)->toBe('+19293394305')
            ->and($updatedMissionary->gender)->toBe(Gender::MALE)
            ->and($updatedMissionary->church)->toBe('Church name')
            ->and($updatedMissionary->offering)->toBe('10.15')
            ->and($updatedMissionary->offering_frequency)->toBe(OfferingFrequency::MONTHLY)
            ->and($updatedMissionary->address)->not->toBeNull();
    });
    it('can be updated with an address when it does not have an address', function (): void {
        $missionary = Missionary::factory()->create();
        from(route('missionaries.edit', ['missionary' => $missionary]))
            ->put(route('missionaries.update', ['missionary' => $missionary]), [
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

        assertDatabaseCount('addresses', 1);

        $updatedMissionary = Missionary::latest()->first();

        expect($updatedMissionary)->not->toBeNull()
            ->and($updatedMissionary->name)->toBe('John')
            ->and($updatedMissionary->last_name)->toBe('Doe')
            ->and($updatedMissionary->email)->toBe('john.doe@example.com')
            ->and($updatedMissionary->phone)->toBe('+19293394305')
            ->and($updatedMissionary->gender)->toBe(Gender::MALE)
            ->and($updatedMissionary->church)->toBe('Church name')
            ->and($updatedMissionary->offering)->toBe('10.15')
            ->and($updatedMissionary->offering_frequency)->toBe(OfferingFrequency::MONTHLY)
            ->and($updatedMissionary->address)->not->toBeNull();
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE);
    });

    it('cannot be rendered if authenticated', function (): void {
        get(route('missionaries.edit', ['missionary' => Missionary::factory()->create()]))
            ->assertForbidden();
    });

    it('cannot be updated', function (): void {
        $missionary = Missionary::factory()->create();
        from(route('missionaries.edit', ['missionary' => $missionary]))
            ->put(route('missionaries.update', ['missionary' => $missionary]), [
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
