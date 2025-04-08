<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can be deleted', function (): void {
    $skill = Tag::factory()->create()->fresh();
    actingAs(User::factory()->create())
        ->delete(route('skills.destroy', ['skill' => $skill]))
        ->assertRedirect(route('skills.index'));

    expect(Tag::find($skill->id))->toBeNull();

});
