<?php

declare(strict_types=1);

use App\Models\Member;

use function Pest\Laravel\get;

test('index page can be rendered', function (): void {
    get(route('members.index'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('members/index')
            ->has('members', 0)
        );
});

test('index page can be rendered with members', function (): void {
    Member::factory()->count(3)->create();
    get(route('members.index'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('members/index')
            ->has('members', 3)
        );
});

test('index page only show not trashed members', function (): void {
    Member::factory()->count(3)->create();
    Member::factory()->count(2)->trashed()->create();
    get(route('members.index'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('members/index')
            ->has('members', 3)
        );
});
