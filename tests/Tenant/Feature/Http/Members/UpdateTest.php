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
    $member = Member::factory()->create();
    get(route('members.edit', ['member' => $member]))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {

    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MEMBERS_MANAGE, TenantPermission::MEMBERS_UPDATE);
    });

    it('can be rendered if authenticated', function (): void {
        $member = Member::factory()->create();
        get(route('members.edit', ['member' => $member]))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('main/members/edit')
                ->has('member')
                ->has('genders')
                ->has('civilStatuses')
                ->has('skills')
                ->has('categories')
            );
    });

    it('can be updated without an address', function (): void {
        $member = Member::factory()->create();

        from(route('members.edit', ['member' => $member]))
            ->put(route('members.update', ['member' => $member]), [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+19293394305',
                'gender' => Gender::MALE->value,
                'dob' => '1990-01-01',
                'baptism_date' => '2000-06-10',
                'civil_status' => CivilStatus::SINGLE->value,
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('members.index'));

        assertDatabaseCount('addresses', 0);

        $member->refresh();

        expect($member->name)->toBe('John')
            ->and($member->last_name)->toBe('Doe')
            ->and($member->email)->toBe('john.doe@example.com')
            ->and($member->phone)->toBe('+19293394305')
            ->and($member->gender)->toBe(Gender::MALE)
            ->and($member->dob->format('Y-m-d'))->toBe('1990-01-01')
            ->and($member->baptism_date->format('Y-m-d'))->toBe('2000-06-10')
            ->and($member->civil_status)->toBe(CivilStatus::SINGLE)
            ->and($member->address)->toBeNull();
    });

    it('can be updated without an address when it already has an address', function (): void {
        $member = Member::factory()->hasAddress()->create();

        from(route('members.edit', ['member' => $member]))
            ->put(route('members.update', ['member' => $member]), [
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

        assertDatabaseCount('addresses', 0);

        $updatedMember = $member->refresh();

        expect($updatedMember)->not->toBeNull()
            ->and($updatedMember->name)->toBe('John')
            ->and($updatedMember->last_name)->toBe('Doe')
            ->and($updatedMember->email)->toBe('john.doe@example.com')
            ->and($updatedMember->phone)->toBe('+19293394305')
            ->and($updatedMember->gender)->toBe(Gender::MALE)
            ->and($updatedMember->dob->format('Y-m-d'))->toBe('1990-01-01')
            ->and($updatedMember->civil_status)->toBe(CivilStatus::SINGLE)
            ->and($updatedMember->address)->toBeNull();
    });

    it('can be updated with an address when it already has an address', function (): void {
        $member = Member::factory()->hasAddress()->create();
        from(route('members.edit', ['member' => $member]))
            ->put(route('members.update', ['member' => $member]), [
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

        assertDatabaseCount('addresses', 1);

        $updatedMember = $member->refresh();

        expect($updatedMember)->not->toBeNull()
            ->and($updatedMember->name)->toBe('John')
            ->and($updatedMember->last_name)->toBe('Doe')
            ->and($updatedMember->email)->toBe('john.doe@example.com')
            ->and($updatedMember->phone)->toBe('+19293394305')
            ->and($updatedMember->gender)->toBe(Gender::MALE)
            ->and($updatedMember->dob->format('Y-m-d'))->toBe('1990-01-01')
            ->and($updatedMember->civil_status)->toBe(CivilStatus::SINGLE)
            ->and($updatedMember->address)->not->toBeNull();
    });

    it('can be updated with an address when it does not have an address', function (): void {
        $member = Member::factory()->create();
        from(route('members.edit', ['member' => $member]))
            ->put(route('members.update', ['member' => $member]), [
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

        assertDatabaseCount('addresses', 1);

        $updatedMember = Member::latest()->first();

        expect($updatedMember)->not->toBeNull()
            ->and($updatedMember->name)->toBe('John')
            ->and($updatedMember->last_name)->toBe('Doe')
            ->and($updatedMember->email)->toBe('john.doe@example.com')
            ->and($updatedMember->phone)->toBe('+19293394305')
            ->and($updatedMember->gender)->toBe(Gender::MALE)
            ->and($updatedMember->dob->format('Y-m-d'))->toBe('1990-01-01')
            ->and($updatedMember->civil_status)->toBe(CivilStatus::SINGLE)
            ->and($updatedMember->address)->not->toBeNull();
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MEMBERS_MANAGE);
    });

    it('cannot be rendered if authenticated', function (): void {
        get(route('members.edit', ['member' => Member::factory()->create()]))
            ->assertForbidden();
    });

    it('cannot be updated', function (): void {
        $member = Member::factory()->create();
        from(route('members.edit', ['member' => $member]))
            ->put(route('members.update', ['member' => $member]), [
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
