<?php

declare(strict_types=1);

use App\Models\Member;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('index page can be rendered if authenticated', function (): void {
    actingAs(User::factory()->create())->get(route('members.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('members/index')
            ->has('members', 0)
        );
});

test('index page can not be rendered if not authenticated', function (): void {
    get(route('members.index'))
        ->assertRedirect(route('login'));
});

test('index page can be rendered with members', function (): void {
    Member::factory()->count(3)->create();

    actingAs(User::factory()->create())->get(route('members.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('members/index')
            ->has('members', 3)
        );
});

test('index page only show not trashed members', function (): void {
    Member::factory()->count(3)->create();
    Member::factory()->count(2)->trashed()->create();

    actingAs(User::factory()->create())->get(route('members.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('members/index')
            ->has('members', 3)
        );
});
