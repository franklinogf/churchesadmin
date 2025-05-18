<?php

declare(strict_types=1);

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Enums\TenantPermission;
use App\Models\Member;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\from;
use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('members.create'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_MEMBERS, TenantPermission::CREATE_MEMBERS);
    });

    it('can be rendered if authenticated', function (): void {

        get(route('members.create'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('members/create')
                ->has('genders')
                ->has('civilStatuses')
                ->has('skills')
                ->has('categories')
            );

    });

    it('can be stored without an address', function (): void {

        from(route('members.create'))
            ->post(route('members.store'), [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+19293394305',
                'gender' => Gender::MALE->value,
                'dob' => '1990-01-01',
                'civil_status' => CivilStatus::SINGLE->value,
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('members.index'));

        assertDatabaseCount('members', 1);
        assertDatabaseCount('addresses', 0);

        $member = Member::latest()->first();

        expect($member)->not->toBeNull()
            ->and($member->name)->toBe('John')
            ->and($member->last_name)->toBe('Doe')
            ->and($member->email)->toBe('john.doe@example.com')
            ->and($member->phone)->toBe('+19293394305')
            ->and($member->gender)->toBe(Gender::MALE)
            ->and($member->dob->format('Y-m-d'))->toBe('1990-01-01')
            ->and($member->civil_status)->toBe(CivilStatus::SINGLE)
            ->and($member->address)->toBeNull();
    });

    it('can be stored with an address', function (): void {

        from(route('members.create'))
            ->post(route('members.store'), [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+19293394305',
                'gender' => Gender::MALE->value,
                'dob' => '1990-01-01',
                'civil_status' => CivilStatus::SINGLE->value,
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
            ->assertRedirect(route('members.index'));

        assertDatabaseCount('members', 1);
        assertDatabaseCount('addresses', 1);

        $member = Member::latest()->first();

        expect($member)->not->toBeNull()
            ->and($member->name)->toBe('John')
            ->and($member->last_name)->toBe('Doe')
            ->and($member->email)->toBe('john.doe@example.com')
            ->and($member->phone)->toBe('+19293394305')
            ->and($member->gender)->toBe(Gender::MALE)
            ->and($member->dob->format('Y-m-d'))->toBe('1990-01-01')
            ->and($member->civil_status)->toBe(CivilStatus::SINGLE)
            ->and($member->address)->not->toBeNull();
    });

});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_MEMBERS);
    });

    it('cannot be rendered if authenticated', function (): void {
        get(route('members.create'))
            ->assertForbidden();
    });

    it('cannot be stored', function (): void {
        from(route('members.create'))
            ->post(route('members.store'), [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+19293394305',
                'gender' => Gender::MALE->value,
                'dob' => '1990-01-01',
                'civil_status' => CivilStatus::SINGLE->value,
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
