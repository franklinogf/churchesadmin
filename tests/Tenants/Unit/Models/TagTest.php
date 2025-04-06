<?php

declare(strict_types=1);

use App\Models\Tag;

test('to array', function (): void {
    $user = Tag::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'slug',
        'type',
        'order_column',
        'created_at',
        'updated_at',
    ]);
});

test('tags can have members', function (): void {
    $tag = Tag::factory()->create();

    $members = App\Models\Member::factory(2)->create();

    $members->each->attachTag($tag);

    expect($tag->members()->count())->toBe(2);
});
