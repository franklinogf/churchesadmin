<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Tag;

use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CATEGORIES_MANAGE, TenantPermission::CATEGORIES_UPDATE, TenantPermission::REGULAR_TAG_UPDATE);
    });

    it('can be updated', function (): void {

        $category = Tag::factory()->category()->create([
            'name' => 'old name',
        ]);

        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => 'new name',
                'is_regular' => true,
            ])->assertRedirect(route('categories.index'));

        $category->refresh();
        expect($category->name)->toBe('new name')
            ->and($category->is_regular)->toBe(true);
    });

    it('cannot be updated with an empty name', function (): void {

        $category = Tag::factory()->category()->create(
            ['name' => 'old name']
        );
        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => '',
                'is_regular' => true,
            ])->assertSessionHasErrors();

        $category->refresh();
        expect($category->name)->toBe('old name')
            ->and($category->is_regular)->toBe(false);
    });

    it('cannot be updated with a name that is too short', function (): void {

        $category = Tag::factory()->category()->create(
            ['name' => 'old name']
        );
        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => 'a',
                'is_regular' => true,
            ])->assertSessionHasErrors();

        $category->refresh();
        expect($category->name)->toBe('old name')
            ->and($category->is_regular)->toBe(false);
    });

    test('can update a regular category', function (): void {
        $category = Tag::factory()->regular()->category()->create(
            ['name' => 'old name']
        );

        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => 'new name',
                'is_regular' => false,
            ])->assertRedirect(route('categories.index'));

        $category->refresh();
        expect($category->name)->toBe('new name')
            ->and($category->is_regular)->toBe(false);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot update a category', function (): void {

        $category = Tag::factory()->category()->create(
            ['name' => 'old name']
        );

        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => 'new name',
                'is_regular' => false,
            ])
            ->assertForbidden();

        $category->refresh();
        expect($category->name)->toBe('old name')
            ->and($category->is_regular)->toBe(false);

    });

    it('cannot update a regular category', function (): void {
        $category = Tag::factory()->regular()->category()->create(
            ['name' => 'old name']
        );
        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => 'new name',
                'is_regular' => false,
            ])
            ->assertForbidden();

        $category->refresh();
        expect($category->name)->toBe('old name')
            ->and($category->is_regular)->toBe(true);
    });

});
