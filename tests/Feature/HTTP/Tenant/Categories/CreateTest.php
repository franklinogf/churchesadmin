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
        asUserWithPermission(TenantPermission::MANAGE_CATEGORIES, TenantPermission::CREATE_CATEGORIES, TenantPermission::CREATE_REGULAR_TAG);
    });

    it('can be stored', function (): void {

        from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('categories.index'));

        assertDatabaseCount('tags', 1);

        $category = Tag::withType(TagType::CATEGORY->value)->first();

        expect($category)->not->toBeNull()
            ->and($category->name)->toBe('tag name')
            ->and($category->type)->toBe(TagType::CATEGORY->value);

    });

    it('cannot be stored with an empty name', function (): void {

        from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => ''],
                'is_regular' => false,
            ])
            ->assertSessionHasErrors();

        assertDatabaseCount('tags', 0);

        $category = Tag::withType(TagType::CATEGORY->value)->first();
        expect($category)->toBeNull();
    });

    it('cannot be stored with a name that is too short', function (): void {

        from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'a'],
                'is_regular' => false,
            ])
            ->assertSessionHasErrors();

        assertDatabaseCount('tags', 0);

        $category = Tag::withType(TagType::CATEGORY->value)->first();
        expect($category)->toBeNull();
    });

    it('cannot be stored if the name already exists', function (): void {

        Tag::factory()->category()->create([
            'name' => ['en' => 'tag name'],
        ]);
        from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('categories.index'));

        assertDatabaseCount('tags', 1);

    });

    test('can store a regular category', function (): void {

        from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'tag name'],
                'is_regular' => true,
            ])
            ->assertRedirect(route('categories.index'));

        assertDatabaseCount('tags', 1);

        $category = Tag::withType(TagType::CATEGORY->value)->first();

        expect($category)->not->toBeNull()
            ->and($category->name)->toBe('tag name')
            ->and($category->type)->toBe(TagType::CATEGORY->value)
            ->and($category->is_regular)->toBe(true);

    });

});

describe('if user does not have permission', function (): void {

    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_CATEGORIES);
    });

    it('cannot be stored', function (): void {

        from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        assertDatabaseCount('tags', 0);

    });

    test('cannot store a regular category', function (): void {

        from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'tag name'],
                'is_regular' => true,
            ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        assertDatabaseCount('tags', 0);

    });
});
