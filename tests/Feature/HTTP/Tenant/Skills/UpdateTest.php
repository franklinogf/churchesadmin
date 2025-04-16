<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Enums\TenantPermission;
use App\Models\Tag;

use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_SKILLS, TenantPermission::UPDATE_SKILLS, TenantPermission::UPDATE_REGULAR_TAG);
    });

    it('can be updated', function (): void {

        $skill = Tag::factory()->skill()->create([
            'name' => ['en' => 'name'],
        ])->fresh();

        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])->assertRedirect(route('skills.index'));

        $updatedSkill = Tag::find($skill->id);

        expect($updatedSkill)->not->toBeNull()
            ->and($updatedSkill->name)->toBe('tag name')
            ->and($updatedSkill->is_regular)->toBe(false);
    });

    it('cannot be updated with an empty name', function (): void {

        $skill = Tag::factory()->skill()->create()->fresh();
        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => ['en' => ''],
                'is_regular' => true,
            ])->assertSessionHasErrors();

        $updatedSkill = Tag::withType(TagType::SKILL->value)->first();
        expect($updatedSkill->name)->not->toBe('')
            ->and($updatedSkill->name)->not->toBeNull()
            ->and($updatedSkill->name)->toBe($skill->name)
            ->and($updatedSkill->is_regular)->toBe(false);
    });

    it('cannot be updated with a name that is too short', function (): void {

        $skill = Tag::factory()->skill()->create()->fresh();
        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
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

    test('can update a regular skill', function (): void {
        $skill = Tag::factory()->regular()->skill()->create()->fresh();

        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])->assertRedirect(route('skills.index'));

        $updatedSkill = Tag::find($skill->id);

        expect($updatedSkill)->not->toBeNull()
            ->and($updatedSkill->name)->toBe('tag name')
            ->and($updatedSkill->is_regular)->toBe(false);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_SKILLS);
    });

    it('cannot update a skill', function (): void {

        $skill = Tag::factory()->skill()->create()->fresh();

        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('skills.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        $updatedSkill = Tag::find($skill->id);
        expect($updatedSkill)->not->toBeNull()
            ->and($updatedSkill->name)->not->toBe('tag name')
            ->and($updatedSkill->name)->not->toBeNull()
            ->and($updatedSkill->name)->toBe($skill->name)
            ->and($updatedSkill->is_regular)->toBe(false);

    });

    it('cannot update a regular skill', function (): void {
        $skill = Tag::factory()->regular()->skill()->create()->fresh();
        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('skills.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        $updatedSkill = Tag::find($skill->id);
        expect($updatedSkill)->not->toBeNull()
            ->and($updatedSkill->name)->not->toBe('tag name')
            ->and($updatedSkill->name)->not->toBeNull()
            ->and($updatedSkill->name)->toBe($skill->name)
            ->and($updatedSkill->is_regular)->toBe(true);
    });

});
