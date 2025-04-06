<?php

declare(strict_types=1);

use App\Enums\LanguageCode;
use App\Models\User;

test('to array', function (): void {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'language',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $user = User::factory()->create()->fresh();

    expect($user->language)->toBeInstanceOf(LanguageCode::class);
});
