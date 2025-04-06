<?php

declare(strict_types=1);

use App\Models\Church;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('to array', function (): void {
    $church = Church::createQuietly([
        'name' => 'Test Church',
    ])->fresh();

    expect(array_keys($church->toArray()))->toBe([
        'id',
        'name',
        'data',
        'created_at',
        'updated_at',
    ]);
});
