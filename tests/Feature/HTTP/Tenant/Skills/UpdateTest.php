<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('updates a skill', function (): void {

    $skill = Tag::factory()->create(['type' => TagType::SKILL->value])->fresh();

    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->put(route('skills.update', ['skill' => $skill]), [
            'name' => ['en' => 'tag name'],
            'is_regular' => false,
        ])->assertRedirect(route('skills.index'));

    $updatedSkill = Tag::find($skill->id);

    expect($updatedSkill)->not->toBeNull()
        ->and($updatedSkill->name)->toBe('tag name')
        ->and($updatedSkill->is_regular)->toBe(false);
});

test('can not update a skill with an empty name', function (): void {

    $skill = Tag::factory()->create(['type' => TagType::SKILL->value])->fresh();
    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->put(route('skills.update', ['skill' => $skill]), [
            'name' => ['en' => ''],
            'is_regular' => true,
        ])->assertSessionHasErrors();

    $updatedSkill = Tag::withType(TagType::SKILL->value)->first();
    expect($updatedSkill->name)->not->toBe('')
        ->and($updatedSkill->name)->not->toBeNull()
        ->and($updatedSkill->name)->toBe($skill->name)
        ->and($updatedSkill->is_regular)->toBe(false);
});

test('cannot update a skill with a name that is too short', function (): void {

    $skill = Tag::factory()->create(['type' => TagType::SKILL->value])->fresh();
    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->put(route('skills.update', ['skill' => $skill]), [
            'name' => ['en' => 'a'],
            'is_regular' => true,
        ])->assertSessionHasErrors();

    $skill->refresh();
    expect($skill)->not->toBeNull()
        ->and($skill->name)->not->toBe('a')
        ->and($skill->name)->not->toBeNull()
        ->and($skill->name)->toBe($skill->name)
        ->and($skill->is_regular)->toBe(false);
});

test('non admin users cannot update a regular skill', function (): void {
    $skill = Tag::factory()->create(['type' => TagType::SKILL->value, 'is_regular' => true])->fresh();
    actingAs(User::factory()->create())
        ->from(route('skills.index'))
        ->put(route('skills.update', ['skill' => $skill]), [
            'name' => ['en' => 'tag name'],
            'is_regular' => false,
        ])->assertSessionHas(FlashMessageKey::ERROR->value);

    $updatedSkill = Tag::find($skill->id);
    expect($updatedSkill)->not->toBeNull()
        ->and($updatedSkill->name)->not->toBe('tag name')
        ->and($updatedSkill->name)->not->toBeNull()
        ->and($updatedSkill->name)->toBe($skill->name)
        ->and($updatedSkill->is_regular)->toBe(true);
});

test('admin users can update a regular skill', function (): void {
    $skill = Tag::factory()->create(['type' => TagType::SKILL->value, 'is_regular' => true])->fresh();
    actingAs(User::factory()->admin()->create())
        ->from(route('skills.index'))
        ->put(route('skills.update', ['skill' => $skill]), [
            'name' => 'tag name',
            'is_regular' => false,
        ])->assertRedirect(route('skills.index'));

    $updatedSkill = Tag::find($skill->id);

    expect($updatedSkill)->not->toBeNull()
        ->and($updatedSkill->name)->toBe('tag name')
        ->and($updatedSkill->is_regular)->toBe(false);
})->skip();
