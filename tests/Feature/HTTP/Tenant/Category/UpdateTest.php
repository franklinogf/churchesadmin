<?php

declare(strict_types=1);

use App\Enums\TagType;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can be updated', function (): void {

    $category = Tag::factory()->create(['type' => TagType::CATEGORY->value])->fresh();

    actingAs(User::factory()->create())
        ->from(route('categories.index'))
        ->put(route('categories.update', ['category' => $category]), [
            'name' => 'tag name',
            'is_regular' => false,
        ])->assertRedirect(route('categories.index'));

    $updatedCategory = Tag::find($category->id);

    expect($updatedCategory)->not->toBeNull()
        ->and($updatedCategory->name)->toBe('tag name')
        ->and($updatedCategory->is_regular)->toBe(false);
});

test('can not be updated with an empty name', function (): void {

    $category = Tag::factory()->create(['type' => TagType::CATEGORY->value])->fresh();
    actingAs(User::factory()->create())
        ->from(route('categories.index'))
        ->put(route('categories.update', ['category' => $category]), [
            'name' => '',
            'is_regular' => true,
        ])->assertSessionHasErrors();

    $updatedCategory = Tag::withType(TagType::CATEGORY->value)->first();
    expect($updatedCategory->name)->not->toBe('')
        ->and($updatedCategory->name)->toBe($category->name)
        ->and($updatedCategory->is_regular)->toBe(false);
});

test('can not be updated with a name that is too short', function (): void {

    $category = Tag::factory()->create(['type' => TagType::CATEGORY->value])->fresh();
    actingAs(User::factory()->create())
        ->from(route('categories.index'))
        ->put(route('categories.update', ['category' => $category]), [
            'name' => 'a',
            'is_regular' => true,
        ])->assertSessionHasErrors();

    $updatedCategory = Tag::withType(TagType::CATEGORY->value)->first();
    expect($updatedCategory)->not->toBeNull()
        ->and($updatedCategory->name)->not->toBe('a')
        ->and($updatedCategory->name)->not->toBeNull()
        ->and($updatedCategory->name)->toBe($category->name)
        ->and($updatedCategory->is_regular)->toBe($category->is_regular);
});

test('non admin users cannot update a regular category', function (): void {
    $category = Tag::factory()->create(['type' => TagType::CATEGORY->value, 'is_regular' => true])->fresh();
    actingAs(User::factory()->create())
        ->from(route('categories.index'))
        ->put(route('categories.update', ['category' => $category]), [
            'name' => 'tag name',
            'is_regular' => false,
        ])->assertRedirect(route('categories.index'))
        ->assertSessionHasErrors();

    $updatedCategory = Tag::withType(TagType::CATEGORY->value)->first();
    expect($updatedCategory)->not->toBeNull()
        ->and($updatedCategory->name)->not->toBeNull()
        ->and($updatedCategory->name)->not->toBe('tag name')
        ->and($updatedCategory->name)->toBe($category->name)
        ->and($updatedCategory->is_regular)->toBe(true);
});

test('admin users can update a regular category', function (): void {
    $category = Tag::factory()->create(['type' => TagType::CATEGORY->value, 'is_regular' => true])->fresh();
    actingAs(User::factory()->admin()->create())
        ->from(route('categories.index'))
        ->put(route('categories.update', ['category' => $category]), [
            'name' => 'tag name',
            'is_regular' => false,
        ])->assertRedirect(route('categories.index'));

    $updatedCategory = Tag::find($category->id);

    expect($updatedCategory)->not->toBeNull()
        ->and($updatedCategory->name)->toBe('tag name')
        ->and($updatedCategory->is_regular)->toBe(false);
})->skip();
