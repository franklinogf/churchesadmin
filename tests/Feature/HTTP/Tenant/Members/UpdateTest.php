<?php

declare(strict_types=1);

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Models\Member;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('edit page can be rendered if authenticated', function (): void {
    $member = Member::factory()->create()->fresh();
    actingAs(User::factory()->create())
        ->get(route('members.edit', ['member' => $member]))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('members/edit')
            ->has('member')
        );
});

test('edit page can not be rendered if not authenticated', function (): void {
    get(route('members.edit', ['member' => 1]))
        ->assertRedirect(route('login'));
});

test('updates a member', function (): void {
    $member = Member::factory()->create()->fresh();
    actingAs(User::factory()->create())
        ->from(route('members.edit', ['member' => $member]))
        ->put(route('members.update', ['member' => $member]), [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => phone('9293394305', 'US')->formatE164(),
            'gender' => Gender::MALE->value,
            'dob' => '1990-01-01',
            'civil_status' => CivilStatus::SINGLE->value,
        ])
        ->assertRedirect(route('members.index'));

    $member = Member::latest()->first();

    expect($member)->not->toBeNull()
        ->and($member->name)->toBe('John')
        ->and($member->last_name)->toBe('Doe')
        ->and($member->email)->toBe('john.doe@example.com')
        ->and($member->phone)->toBe('+19293394305')
        ->and($member->gender)->toBe(Gender::MALE)
        ->and($member->dob->format('Y-m-d'))->toBe('1990-01-01')
        ->and($member->civil_status)->toBe(CivilStatus::SINGLE);
});
