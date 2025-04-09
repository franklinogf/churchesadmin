<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Enums\TenantPermissionName;
use App\Models\Tag;

describe('if user has permission', function (): void {

    test('can be stored', function (): void {

        asUserWithPermission(TenantPermissionName::CREATE_CATEGORIES)
            ->from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])->assertRedirect(route('categories.index'));

        $category = Tag::withType(TagType::CATEGORY->value)->first();

        expect($category)->not->toBeNull()
            ->and($category->name)->toBe('tag name')
            ->and($category->type)->toBe(TagType::CATEGORY->value);

    });

    test('cannot be stored with an empty name', function (): void {

        asUserWithPermission(TenantPermissionName::CREATE_CATEGORIES)
            ->from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => ''],
                'is_regular' => false,
            ])->assertSessionHasErrors();

        $category = Tag::withType(TagType::CATEGORY->value)->first();
        expect($category)->toBeNull();
    });

    test('cannot be stored with a name that is too short', function (): void {

        asUserWithPermission(TenantPermissionName::CREATE_CATEGORIES)
            ->from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'a'],
                'is_regular' => false,
            ])->assertSessionHasErrors();

        $category = Tag::withType(TagType::CATEGORY->value)->first();
        expect($category)->toBeNull();
    });

    test('cannot be stored with an existing name', function (): void {
        Tag::factory()->create([
            'name' => ['en' => 'tag name'],
            'type' => TagType::CATEGORY->value,
        ]);

        asUserWithPermission(TenantPermissionName::CREATE_CATEGORIES)
            ->from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])->assertRedirect(route('categories.index'));
        $categories = Tag::withType(TagType::CATEGORY->value)->count();
        expect($categories)->toBe(1);

    });
});

describe('if user doesn\'t have permission', function (): void {

    test('cannot be stored', function (): void {

        asUserWithoutPermission()
            ->from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => ['en' => 'tag name'],
                'is_regular' => false,
            ])->assertRedirect(route('categories.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);

        $category = Tag::withType(TagType::CATEGORY->value)->first();

        expect($category)->toBeNull();

    });

});
