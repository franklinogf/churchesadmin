<?php

declare(strict_types=1);

use App\Enums\TagType;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can be stored', function (): void {

    actingAs(User::factory()->create())
        ->from(route('categories.index'))
        ->post(route('categories.store'), [
            'name' => 'tag name',
        ])->assertRedirect(route('categories.index'));

    $category = Tag::withType(TagType::CATEGORY->value)->first();

    expect($category)->not->toBeNull()
        ->and($category->name)->toBe('tag name')
        ->and($category->type)->toBe(TagType::CATEGORY->value);

});

test('can not be stored with an empty name', function (): void {

    actingAs(User::factory()->create())
        ->from(route('categories.index'))
        ->post(route('categories.store'), [
            'name' => '',
        ])->assertSessionHasErrors();

    $category = Tag::withType(TagType::CATEGORY->value)->first();
    expect($category)->toBeNull();
});

test('can not be stored a category with a name that is too short', function (): void {

    actingAs(User::factory()->create())
        ->from(route('categories.index'))
        ->post(route('categories.store'), [
            'name' => 'a',
        ])->assertSessionHasErrors();

    $category = Tag::withType(TagType::CATEGORY->value)->first();
    expect($category)->toBeNull();
});

test('can not be stored a category with an existing name', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->from(route('categories.index'))
        ->post(route('categories.store'), [
            'name' => 'tag name',
        ])->assertRedirect(route('categories.index'));

    actingAs($user)
        ->from(route('categories.index'))
        ->post(route('categories.store'), [
            'name' => 'tag name',
        ])->assertRedirect(route('categories.index'));
    $categories = Tag::withType(TagType::CATEGORY->value)->count();
    expect($categories)->toBe(1);

});
