<?php

declare(strict_types=1);

use App\Models\TenantUser;

test('to array', function (): void {
    $user = TenantUser::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'timezone',
        'timezone_country',
        'created_at',
        'updated_at',
    ]);
});
