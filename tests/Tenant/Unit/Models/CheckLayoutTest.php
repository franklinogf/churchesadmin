<?php

declare(strict_types=1);

use App\Models\CheckLayout;
use App\Models\ChurchWallet;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $checkLayout = CheckLayout::factory()->create()->fresh();

    expect(array_keys($checkLayout->toArray()))->toBe([
        'id',
        'name',
        'width',
        'height',
        'fields',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $checkLayout = CheckLayout::factory()->create()->fresh();

    expect($checkLayout->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($checkLayout->updated_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($checkLayout->fields)->toBeArray();
});

it('can have wallets', function (): void {
    $checkLayout = CheckLayout::factory()
        ->has(ChurchWallet::factory()->count(2), 'wallets')
        ->create();

    expect($checkLayout->wallets)->toHaveCount(2);
    expect($checkLayout->wallets->first())->toBeInstanceOf(ChurchWallet::class);
});

it('implements media interface', function (): void {
    $checkLayout = CheckLayout::factory()->create();

    expect($checkLayout)->toBeInstanceOf(Spatie\MediaLibrary\HasMedia::class);
});

it('can register media collections', function (): void {
    $checkLayout = CheckLayout::factory()->create();

    $checkLayout->registerMediaCollections();

    expect($checkLayout->getMediaCollection('default'))->not->toBeNull();
});

it('returns empty string for image url when no media exists', function (): void {
    $checkLayout = CheckLayout::factory()->create();

    expect($checkLayout->imageUrl)->toBe('');
});

it('can have fields as json', function (): void {
    $fields = [
        'date' => ['position' => ['x' => 100, 'y' => 50]],
        'amount' => ['position' => ['x' => 200, 'y' => 100]],
    ];

    $checkLayout = CheckLayout::factory()->create([
        'fields' => $fields,
    ]);

    expect($checkLayout->fields)->toBe($fields);
});

it('has width and height properties', function (): void {
    $checkLayout = CheckLayout::factory()->create([
        'width' => 800,
        'height' => 600,
    ]);

    expect($checkLayout->width)->toBe(800);
    expect($checkLayout->height)->toBe(600);
});
