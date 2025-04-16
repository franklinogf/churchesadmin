<?php

declare(strict_types=1);

use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

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
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'language' => 'en',
    ])->fresh();

    expect($user->email_verified_at)->toBeInstanceOf(Carbon\CarbonImmutable::class);
    expect($user->language)->toBeInstanceOf(App\Enums\LanguageCode::class);
});
