<?php

declare(strict_types=1);

use App\Models\CurrentYear;
use App\Models\Email;
use App\Models\TenantUser;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $user = TenantUser::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'timezone',
        'timezone_country',
        'current_year_id',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $user = TenantUser::factory()->create()->fresh();

    expect($user->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($user->updated_at)->toBeInstanceOf(CarbonImmutable::class);

    if ($user->email_verified_at) {
        expect($user->email_verified_at)->toBeInstanceOf(CarbonImmutable::class);
    }
});

test('hidden attributes are not in array', function (): void {
    $user = TenantUser::factory()->create()->fresh();

    expect($user->toArray())->not->toHaveKey('password');
    expect($user->toArray())->not->toHaveKey('remember_token');
});

it('can have sent emails', function (): void {
    $user = TenantUser::factory()
        ->has(Email::factory()->count(3), 'emails')
        ->create();

    expect($user->emails)->toHaveCount(3);
    expect($user->emails[0])->toBeInstanceOf(Email::class);
});

it('belongs to a current year', function (): void {
    $user = TenantUser::factory()->create()->fresh();

    expect($user->currentYear)->toBeInstanceOf(CurrentYear::class);
    expect($user->currentYear->id)->toBe($user->current_year_id);
});

it('implements must verify email interface', function (): void {
    $user = TenantUser::factory()->create();

    expect($user)->toBeInstanceOf(Illuminate\Contracts\Auth\MustVerifyEmail::class);
});

it('uses uuid for primary key', function (): void {
    $user = TenantUser::factory()->create();

    expect($user->id)->toBeString();
    expect(mb_strlen((string) $user->id))->toBe(36); // UUID length
});

it('uses roles trait', function (): void {
    $user = TenantUser::factory()->create();

    expect(method_exists($user, 'assignRole'))->toBeTrue();
    expect(method_exists($user, 'hasRole'))->toBeTrue();
});

it('uses notifiable trait', function (): void {
    $user = TenantUser::factory()->create();

    expect(method_exists($user, 'notify'))->toBeTrue();
    expect(method_exists($user, 'notifications'))->toBeTrue();
});

it('can have timezone information', function (): void {
    $user = TenantUser::factory()->create([
        'timezone' => 'America/New_York',
        'timezone_country' => 'US',
    ]);

    expect($user->timezone)->toBe('America/New_York');
    expect($user->timezone_country)->toBe('US');
});
