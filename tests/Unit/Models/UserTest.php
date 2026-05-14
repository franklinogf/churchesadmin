<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('to array', function (): void {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
    ]);
});

test('can access panel', function (): void {
    $user = User::factory()->create();

    expect($user->canAccessPanel(app(Panel::class)))->toBeTrue();
});
