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
