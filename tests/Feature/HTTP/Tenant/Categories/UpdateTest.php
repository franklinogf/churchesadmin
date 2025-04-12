<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Enums\TenantPermission;
use App\Models\Tag;

use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_CATEGORIES, TenantPermission::UPDATE_CATEGORIES, TenantPermission::UPDATE_REGULAR_TAG);
    });

    it('can be updated', function (): void {

        $category = Tag::factory()->category()->create([
            'name' => ['en' => 'name'],
        ])->fresh();

        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('categories.index'));

        $updatedCategory = Tag::find($category->id);

        expect($updatedCategory)->not->toBeNull()
            ->and($updatedCategory->name)->toBe('tag name')
            ->and($updatedCategory->is_regular)->toBe(false);
    });

    it('cannot be updated with an empty name', function (): void {

        $category = Tag::factory()->category()->create()->fresh();
        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => ['en' => ''],
                'is_regular' => true,
            ])
            ->assertSessionHasErrors();

        $updatedCategory = Tag::withType(TagType::CATEGORY->value)->first();
        expect($updatedCategory->name)->not->toBe('')
            ->and($updatedCategory->name)->not->toBeNull()
            ->and($updatedCategory->name)->toBe($category->name)
            ->and($updatedCategory->is_regular)->toBe(false);
    });

    it('cannot be updated with a name that is too short', function (): void {

        $category = Tag::factory()->category()->create()->fresh();
        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => ['en' => 'a'],
                'is_regular' => true,
            ])
            ->assertSessionHasErrors();

        $category->refresh();
        expect($category)->not->toBeNull()
            ->and($category->name)->not->toBe('a')
            ->and($category->name)->not->toBeNull()
            ->and($category->name)->toBe($category->name)
            ->and($category->is_regular)->toBe(false);
    });

    test('can update a regular category', function (): void {
        $category = Tag::factory()->regular()->category()->create()->fresh();

        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('categories.index'));

        $updatedCategory = Tag::find($category->id);

        expect($updatedCategory)->not->toBeNull()
            ->and($updatedCategory->name)->toBe('tag name')
            ->and($updatedCategory->is_regular)->toBe(false);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_CATEGORIES);
    });

    it('cannot update a category', function (): void {

        $category = Tag::factory()->category()->create()->fresh();

        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        $updatedCategory = Tag::find($category->id);
        expect($updatedCategory)->not->toBeNull()
            ->and($updatedCategory->name)->not->toBe('tag name')
            ->and($updatedCategory->name)->not->toBeNull()
            ->and($updatedCategory->name)->toBe($category->name)
            ->and($updatedCategory->is_regular)->toBe(false);

    });

    it('cannot update a regular category', function (): void {
        $category = Tag::factory()->regular()->category()->create()->fresh();
        from(route('categories.index'))
            ->put(route('categories.update', ['tag' => $category]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        $updatedCategory = Tag::find($category->id);
        expect($updatedCategory)->not->toBeNull()
            ->and($updatedCategory->name)->not->toBe('tag name')
            ->and($updatedCategory->name)->not->toBeNull()
            ->and($updatedCategory->name)->toBe($category->name)
            ->and($updatedCategory->is_regular)->toBe(true);
    });

});
