<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Tag;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\from;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_CATEGORIES, TenantPermission::DELETE_CATEGORIES, TenantPermission::DELETE_REGULAR_TAG);
    });

    it('can be deleted', function (): void {
        $category = Tag::factory()->category()->create();

        from(route('categories.index'))->delete(route('categories.destroy', ['tag' => $category]))
            ->assertRedirect(route('categories.index'));

        assertDatabaseCount('tags', 0);

    });

    test('can delete a regular category', function (): void {
        $category = Tag::factory()->category()->regular()->create();

        from(route('categories.index'))
            ->delete(route('categories.destroy', ['tag' => $category]))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::SUCCESS->value);

        assertDatabaseCount('tags', 0);
    });

});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_CATEGORIES);
    });

    test('cannot delete a regular category', function (): void {
        $category = Tag::factory()->category()->regular()->create();

        from(route('categories.index'))
            ->delete(route('categories.destroy', ['tag' => $category]))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        assertDatabaseCount('tags', 1);
    });
});
