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
