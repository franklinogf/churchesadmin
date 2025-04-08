<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can be deleted', function (): void {
    $category = Tag::factory()->create()->fresh();
    actingAs(User::factory()->create())
        ->delete(route('categories.destroy', ['category' => $category]))
        ->assertRedirect(route('categories.index'));

    expect(Tag::find($category->id))->toBeNull();

});

test('non admin users cannot delete regular categories', function (): void {
    $category = Tag::factory()->create(['is_regular' => true])->fresh();
    actingAs(User::factory()->create())
        ->delete(route('categories.destroy', ['category' => $category]))
        ->assertRedirect(route('categories.index'))
        ->assertSessionHas(FlashMessageKey::ERROR->value);

    expect(Tag::find($category->id))->not()->toBeNull();
});

test('admin users can delete regular categories', function (): void {
    $category = Tag::factory()->create(['is_regular' => true])->fresh();
    actingAs(User::factory()->admin()->create())
        ->delete(route('categories.destroy', ['category' => $category]))
        ->assertRedirect(route('categories.index'));

    expect(Tag::find($category->id))->toBeNull();
})->skip();
