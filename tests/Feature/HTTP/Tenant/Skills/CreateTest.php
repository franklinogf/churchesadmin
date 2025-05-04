<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Enums\TenantPermission;
use App\Models\Tag;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\from;

describe('if user has permission', function (): void {

    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_SKILLS, TenantPermission::CREATE_SKILLS, TenantPermission::CREATE_REGULAR_TAG);
    });

    it('can be stored', function (): void {

        from(route('skills.index'))
            ->post(route('skills.store'), [
                'name' => 'skill name',
                'is_regular' => false,
            ])->assertRedirect(route('skills.index'));

        assertDatabaseCount('tags', 1);

        $skill = Tag::withType(TagType::SKILL->value)->first();

        expect($skill->name)->toBe('skill name')
            ->and($skill->type)->toBe(TagType::SKILL->value);

    });

    it('cannot be stored with an empty name', function (): void {

        from(route('skills.index'))
            ->post(route('skills.store'), [
                'name' => '',
                'is_regular' => false,
            ])->assertSessionHasErrors();

        assertDatabaseCount('tags', 0);

        expect(Tag::withType(TagType::SKILL->value)->count())->toBe(0);
    });

    it('cannot be stored with a name that is too short', function (): void {

        from(route('skills.index'))
            ->post(route('skills.store'), [
                'name' => 'a',
                'is_regular' => false,
            ])->assertSessionHasErrors();

        assertDatabaseCount('tags', 0);

        expect(Tag::withType(TagType::SKILL->value)->count())->toBe(0);
    });

    it('cannot be stored if the name already exists', function (): void {

        Tag::factory()->skill()->create([
            'name' => 'skill name',
        ]);
        from(route('skills.index'))
            ->post(route('skills.store'), [
                'name' => 'skill name',
                'is_regular' => false,
            ])->assertRedirect(route('skills.index'));
        expect(Tag::withType(TagType::SKILL->value)->count())->toBe(1);

    });

    test('can store a regular skill', function (): void {

        from(route('skills.index'))
            ->post(route('skills.store'), [
                'name' => 'skill name',
                'is_regular' => true,
            ])->assertRedirect(route('skills.index'));

        assertDatabaseCount('tags', 1);

        $skill = Tag::withType(TagType::SKILL->value)->first();

        expect($skill->name)->toBe('skill name')
            ->and($skill->type)->toBe(TagType::SKILL->value)
            ->and($skill->is_regular)->toBe(true);

    });

});

describe('if user does not have permission', function (): void {

    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_SKILLS);
    });

    it('cannot be stored', function (): void {

        from(route('skills.index'))
            ->post(route('skills.store'), [
                'name' => 'skill name',
                'is_regular' => false,
            ])
            ->assertRedirect(route('skills.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        assertDatabaseCount('tags', 0);

    });

    test('cannot store a regular skill', function (): void {

        from(route('skills.index'))
            ->post(route('skills.store'), [
                'name' => 'skill name',
                'is_regular' => true,
            ])
            ->assertRedirect(route('skills.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        assertDatabaseCount('tags', 0);

    });
});
