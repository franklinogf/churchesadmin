<?php

declare(strict_types=1);

use App\Models\Church;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $church = Church::factory()->create()->fresh();

    expect(array_keys($church->toArray()))->toBe([
        'id',
        'name',
        'locale',
        'active',
        'data',
        'created_at',
        'updated_at',
        'tenancy_db_name',
        'media',
    ]);
});

test('casts are applied correctly', function (): void {
    $church = Church::factory()->create()->fresh();

    expect($church->active)->toBeBool();
    expect($church->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($church->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('has custom columns defined', function (): void {
    $customColumns = Church::getCustomColumns();

    expect($customColumns)->toContain('name');
    expect($customColumns)->toContain('locale');
    expect($customColumns)->toContain('active');
});

it('can register media collections', function (): void {
    $church = Church::factory()->create();

    $church->registerMediaCollections();

    expect($church->getMediaCollection('logo'))->not->toBeNull();
});

it('returns null logo when no media exists', function (): void {
    $church = Church::factory()->create();

    expect($church->logo)->toBeNull();
});

it('returns logo path when no media exists', function (): void {
    $church = Church::factory()->create();

    expect($church->logoPath)->toBeNull();
});

it('implements wallet interfaces', function (): void {
    $church = Church::factory()->create();

    expect($church)->toBeInstanceOf(Bavix\Wallet\Interfaces\Wallet::class);
    expect($church)->toBeInstanceOf(Bavix\Wallet\Interfaces\WalletFloat::class);
});

it('implements media interface', function (): void {
    $church = Church::factory()->create();

    expect($church)->toBeInstanceOf(Spatie\MediaLibrary\HasMedia::class);
});

it('implements tenant interface', function (): void {
    $church = Church::factory()->create();

    expect($church)->toBeInstanceOf(Stancl\Tenancy\Database\Contracts\TenantWithDatabase::class);
});
