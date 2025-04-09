<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Enums\TenantPermissionName;
use App\Models\Tag;

describe('user has permission', function (): void {

    test('can be updated', function (): void {

        $category = Tag::factory()->category()->create()->fresh();

        asUserWithPermission(TenantPermissionName::UPDATE_CATEGORIES)
            ->from(route('categories.index'))
            ->put(route('categories.update', ['category' => $category->id]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])->assertRedirect(route('categories.index'));

        $updatedCategory = Tag::find($category->id);

        expect($updatedCategory->name)->toBe('tag name')
            ->and($updatedCategory->is_regular)->toBe(false);
    });

    test('cannot be updated with an empty name', function (): void {

        $category = Tag::factory()->category()->create([
            'is_regular' => false,
        ])->fresh();
        asUserWithPermission(TenantPermissionName::UPDATE_CATEGORIES)
            ->from(route('categories.index'))
            ->put(route('categories.update', ['category' => $category->id]), [
                'name' => ['en' => ''],
                'is_regular' => true,
            ])->assertSessionHasErrors();

        $updatedCategory = Tag::withType(TagType::CATEGORY->value)->first();
        expect($updatedCategory->name)->not->toBe('')
            ->and($updatedCategory->name)->toBe($category->name)
            ->and($updatedCategory->is_regular)->toBe(false);
    });

    test('cannot be updated with a name that is too short', function (): void {

        $category = Tag::factory()->category()->create([
            'is_regular' => false,
        ])->fresh();
        asUserWithPermission(TenantPermissionName::UPDATE_CATEGORIES)
            ->from(route('categories.index'))
            ->put(route('categories.update', ['category' => $category]), [
                'name' => ['en' => 'a'],
                'is_regular' => true,
            ])->assertSessionHasErrors();

        $updatedCategory = Tag::withType(TagType::CATEGORY->value)->first();
        expect($updatedCategory->name)->not->toBe('a')
            ->and($updatedCategory->name)->not->toBeNull()
            ->and($updatedCategory->name)->toBe($category->name)
            ->and($updatedCategory->is_regular)->toBe($category->is_regular);
    });

    test('can update a regular category', function (): void {
        $category = Tag::factory()->category()->regular()->create()->fresh();
        asUserWithPermission(TenantPermissionName::UPDATE_REGULAR_TAG, TenantPermissionName::UPDATE_CATEGORIES)
            ->from(route('categories.index'))
            ->put(route('categories.update', ['category' => $category]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])->assertRedirect(route('categories.index'));

        $updatedCategory = Tag::find($category->id);

        expect($updatedCategory->name)->toBe('tag name')
            ->and($updatedCategory->is_regular)->toBe(false);
    });
});

describe('user does not have permission', function () {

    test('cannot update', function (): void {
        $category = Tag::factory()->category()->create()->fresh();

        asUserWithoutPermission()
            ->from(route('categories.index'))
            ->put(route('categories.update', ['category' => $category]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => true,
            ])->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        $updatedCategory = Tag::withType(TagType::CATEGORY->value)->first();

        expect($updatedCategory->name)->not->toBeNull()
            ->and($updatedCategory->name)->not->toBe('tag name')
            ->and($updatedCategory->name)->toBe($category->name)
            ->and($updatedCategory->is_regular)->toBe(false);
    });

    test('cannot update a regular category', function (): void {

        $category = Tag::factory()->category()->regular()->create()->fresh();

        asUserWithPermission(TenantPermissionName::UPDATE_CATEGORIES)
            ->from(route('categories.index'))
            ->put(route('categories.update', ['category' => $category]), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        $updatedCategory = Tag::withType(TagType::CATEGORY->value)->first();

        expect($updatedCategory->name)->not->toBeNull()
            ->and($updatedCategory->name)->not->toBe('tag name')
            ->and($updatedCategory->name)->toBe($category->name)
            ->and($updatedCategory->is_regular)->toBe(true);
    });
});
