<?php

declare(strict_types=1);

use App\Models\Missionary;

test('to array', function (): void {
    $missionary = Missionary::factory()->create()->fresh();

    expect(array_keys($missionary->toArray()))->toBe([
        'id',
        'name',
        'last_name',
        'email',
        'phone',
        'gender',
        'church',
        'offering',
        'offering_frequency',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});
