<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermissionName;
use App\Models\Tag;

describe('if user has permission', function (): void {
    test('can be deleted', function (): void {
        $category = Tag::factory()->category()->create();
        asUserWithPermission(TenantPermissionName::DELETE_CATEGORIES)
            ->from(route('categories.index'))
            ->delete(route('categories.destroy', ['category' => $category->id]))
            ->assertRedirect(route('categories.index'));

        expect(Tag::find($category->id))->toBeNull();

    });
    test('can delete regular categories', function (): void {
        $category = Tag::factory()->category()->regular()->create();
        asUserWithPermission(TenantPermissionName::DELETE_REGULAR_TAG, TenantPermissionName::DELETE_CATEGORIES)
            ->from(route('categories.index'))
            ->delete(route('categories.destroy', ['category' => $category->id]))
            ->assertRedirect(route('categories.index'));

        expect(Tag::find($category->id))->toBeNull();
    });
});

describe('if user doesn\'t have permission', function (): void {
    test('cannot be deleted', function (): void {
        $category = Tag::factory()->category()->create()->fresh();
        asUserWithoutPermission()
            ->from(route('categories.index'))
            ->delete(route('categories.destroy', ['category' => $category->id]))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        expect(Tag::find($category->id))->not->toBeNull();

    });

    test('cannot delete regular categories', function (): void {
        $category = Tag::factory()->category()->regular()->create();
        asUserWithPermission(TenantPermissionName::DELETE_CATEGORIES)
            ->from(route('categories.index'))
            ->delete(route('categories.destroy', ['category' => $category->id]))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        expect(Tag::find($category->id))->not->toBeNull();
    });
});
