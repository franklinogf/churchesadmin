<?php

declare(strict_types=1);

use App\Enums\TagType;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('updates a skill', function (): void {

    $skill = Tag::factory()->create(['type' => TagType::SKILL->value])->fresh();

    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->put(route('skills.update', ['skill' => $skill]), [
            'name' => 'tag name',
        ])->assertRedirect(route('skills.index'));

    $updatedSkill = Tag::find($skill->id);

    expect($updatedSkill)->not->toBeNull()
        ->and($updatedSkill->name)->toBe('tag name');
});

test('can not update a skill with an empty name', function (): void {

    $skill = Tag::factory()->create(['type' => TagType::SKILL->value])->fresh();
    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->put(route('skills.update', ['skill' => $skill]), [
            'name' => '',
        ])->assertSessionHasErrors();

    $updatedSkill = Tag::withType(TagType::SKILL->value)->first();
    expect($updatedSkill->name)->not->toBe('')
        ->and($updatedSkill->name)->not->toBeNull()
        ->and($updatedSkill->name)->toBe($skill->name);
});

test('can not update a skill with a name that is too short', function (): void {

    $skill = Tag::factory()->create(['type' => TagType::SKILL->value])->fresh();
    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->put(route('skills.update', ['skill' => $skill]), [
            'name' => 'a',
        ])->assertSessionHasErrors();

    $updatedSkill = Tag::withType(TagType::SKILL->value)->first();
    expect($updatedSkill)->not->toBeNull()
        ->and($updatedSkill->name)->not->toBe('a')
        ->and($updatedSkill->name)->not->toBeNull()
        ->and($updatedSkill->name)->toBe($skill->name);
});
