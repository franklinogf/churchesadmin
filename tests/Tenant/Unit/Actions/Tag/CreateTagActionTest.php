<?php

declare(strict_types=1);

use App\Actions\Tag\CreateTagAction;
use App\Enums\TagType;
use App\Models\Tag;

it('can create a tag with specific type', function (): void {
    $tagData = [
        'name' => 'Programming',
        'is_regular' => true,
    ];

    $action = new CreateTagAction();
    $action->handle($tagData, TagType::SKILL);

    $tag = Tag::latest()->first();

    expect($tag)->not->toBeNull()
        ->and($tag->name)->toBe('Programming')
        ->and($tag->type)->toBe(TagType::SKILL->value)
        ->and($tag->is_regular)->toBeTrue();

});

it('can create a tag without specific type', function (): void {
    $tagData = [
        'name' => 'General Tag',
        'is_regular' => false,
    ];

    $action = new CreateTagAction();
    $action->handle($tagData);

    $tag = Tag::latest()->first();

    expect($tag)->not->toBeNull()
        ->and($tag->name)->toBe('General Tag')
        ->and($tag->type)->toBeNull()
        ->and($tag->is_regular)->toBeFalse();

});

it('can create regular and non-regular tags', function (): void {
    $regularTagData = [
        'name' => 'Regular Tag',
        'is_regular' => true,
    ];

    $nonRegularTagData = [
        'name' => 'Non Regular Tag',
        'is_regular' => false,
    ];

    $action = new CreateTagAction();
    $action->handle($regularTagData, TagType::SKILL);
    $action->handle($nonRegularTagData, TagType::CATEGORY);

    $regularTag = Tag::where('is_regular', true)->first();
    $nonRegularTag = Tag::where('is_regular', false)->first();

    expect($regularTag)->not->toBeNull()
        ->and($regularTag->is_regular)->toBeTrue()
        ->and($regularTag->type)->toBe(TagType::SKILL->value);

    expect($nonRegularTag)->not->toBeNull()
        ->and($nonRegularTag->is_regular)->toBeFalse()
        ->and($nonRegularTag->type)->toBe(TagType::CATEGORY->value);
});
