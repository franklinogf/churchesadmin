<?php

declare(strict_types=1);

use App\Enums\TagType;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('stores a skill', function (): void {

    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->post(route('skills.store'), [
            'name' => 'tag name',
        ])->assertRedirect(route('skills.index'));

    $skill = Tag::withType(TagType::SKILL->value)->first();

    expect($skill)->not->toBeNull()
        ->and($skill->name)->toBe('tag name')
        ->and($skill->type)->toBe(TagType::SKILL->value);

});

test('can not store a skill with an empty name', function (): void {

    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->post(route('skills.store'), [
            'name' => '',
        ])->assertSessionHasErrors();

    $skill = Tag::withType(TagType::SKILL->value)->first();
    expect($skill)->toBeNull();
});

test('can not store a skill with a name that is too short', function (): void {

    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->post(route('skills.store'), [
            'name' => 'a',
        ])->assertSessionHasErrors();

    $skill = Tag::withType(TagType::SKILL->value)->first();
    expect($skill)->toBeNull();
});

test('can not store a skill with an existing name', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->from(route('skills.index'))
        ->post(route('skills.store'), [
            'name' => 'tag name',
        ])->assertRedirect(route('skills.index'));

    actingAs($user)
        ->from(route('skills.index'))
        ->post(route('skills.store'), [
            'name' => 'tag name',
        ])->assertRedirect(route('skills.index'));
    $skills = Tag::withType(TagType::SKILL->value)->count();
    expect($skills)->toBe(1);

});
