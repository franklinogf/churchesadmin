<?php

declare(strict_types=1);

use App\Actions\Tag\UpdateTagAction;
use App\Enums\TagType;
use App\Models\Tag;

it('can update tag name and regular status', function (): void {
    $tag = Tag::factory()->create();

    $updateData = [
        'name' => 'Updated Name',
        'is_regular' => true,
    ];

    $action = new UpdateTagAction();
    $action->handle($tag, $updateData);

    $tag->refresh();

    expect($tag->is_regular)->toBeTrue();

});

it('can update only the name', function (): void {
    $tag = Tag::factory()->regular()->create();

    $originalRegularStatus = $tag->is_regular;

    $updateData = [
        'name' => 'New Name Only',
    ];

    $action = new UpdateTagAction();
    $action->handle($tag, $updateData);

    $tag->refresh();

    expect($tag->is_regular)->toBe($originalRegularStatus)
        ->and($tag->name)->toBe('New Name Only');

});

it('can toggle regular status', function (): void {
    $tag = Tag::factory()->regular()->create();

    $updateData = [
        'name' => 'Toggle Regular Status',
        'is_regular' => false,
    ];

    $action = new UpdateTagAction();
    $action->handle($tag, $updateData);

    $tag->refresh();

    expect($tag->is_regular)->toBeFalse();
});

it('preserves tag type during update', function (): void {
    $tag = Tag::factory()->create([
        'type' => TagType::SKILL->value,
        'is_regular' => false,
    ]);

    $originalType = $tag->type;

    $updateData = [
        'name' => 'Type Should Stay Same',
        'is_regular' => true,
    ];

    $action = new UpdateTagAction();
    $action->handle($tag, $updateData);

    $tag->refresh();

    expect($tag->type)->toBe($originalType)
        ->and($tag->is_regular)->toBeTrue();
});
