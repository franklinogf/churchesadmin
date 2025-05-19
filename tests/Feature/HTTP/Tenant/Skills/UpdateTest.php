<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Tag;

use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::SKILLS_MANAGE, TenantPermission::SKILLS_UPDATE, TenantPermission::REGULAR_TAG_UPDATE);
    });

    it('can be updated', function (): void {

        $skill = Tag::factory()->skill()->create([
            'name' => 'old name',
        ]);

        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => 'new name',
                'is_regular' => true,
            ])->assertRedirect(route('skills.index'));

        $skill->refresh();
        expect($skill->name)->toBe('new name')
            ->and($skill->is_regular)->toBe(true);
    });

    it('cannot be updated with an empty name', function (): void {

        $skill = Tag::factory()->skill()->create(
            ['name' => 'old name']
        );
        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => '',
                'is_regular' => true,
            ])->assertSessionHasErrors();

        $skill->refresh();
        expect($skill->name)->toBe('old name')
            ->and($skill->is_regular)->toBe(false);
    });

    it('cannot be updated with a name that is too short', function (): void {

        $skill = Tag::factory()->skill()->create(
            ['name' => 'old name']
        );
        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => 'a',
                'is_regular' => true,
            ])->assertSessionHasErrors();

        $skill->refresh();
        expect($skill->name)->toBe('old name')
            ->and($skill->is_regular)->toBe(false);
    });

    test('can update a regular skill', function (): void {
        $skill = Tag::factory()->regular()->skill()->create(
            ['name' => 'old name']
        );

        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => 'new name',
                'is_regular' => false,
            ])->assertRedirect(route('skills.index'));

        $skill->refresh();
        expect($skill->name)->toBe('new name')
            ->and($skill->is_regular)->toBe(false);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::SKILLS_MANAGE);
    });

    it('cannot update a skill', function (): void {

        $skill = Tag::factory()->skill()->create(
            ['name' => 'old name']
        );

        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => 'new name',
                'is_regular' => false,
            ])
            ->assertForbidden();

        $skill->refresh();
        expect($skill->name)->toBe('old name')
            ->and($skill->is_regular)->toBe(false);

    });

    it('cannot update a regular skill', function (): void {
        $skill = Tag::factory()->regular()->skill()->create(
            ['name' => 'old name']
        );
        from(route('skills.index'))
            ->put(route('skills.update', ['tag' => $skill]), [
                'name' => 'new name',
                'is_regular' => false,
            ])
            ->assertForbidden();

        $skill->refresh();
        expect($skill->name)->toBe('old name')
            ->and($skill->is_regular)->toBe(true);
    });

});
