<?php

declare(strict_types=1);

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

test('non admin users cannot delete regular skills', function (): void {
    $skill = Tag::factory()->create(['is_regular' => true])->fresh();
    actingAs(User::factory()->create())
        ->delete(route('skills.destroy', ['skill' => $skill]))
        ->assertRedirect(route('skills.index'))
        ->assertSessionHasErrors();

    expect(Tag::find($skill->id))->not()->toBeNull();
});

test('admin users can delete regular skills', function (): void {
    $skill = Tag::factory()->create(['is_regular' => true])->fresh();
    actingAs(User::factory()->admin()->create())
        ->delete(route('skills.destroy', ['skill' => $skill]))
        ->assertRedirect(route('skills.index'));

    expect(Tag::find($skill->id))->toBeNull();
})->skip();
