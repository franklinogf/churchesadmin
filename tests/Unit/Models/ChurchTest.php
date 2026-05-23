<?php

declare(strict_types=1);

use App\Data\TenantFeatures;
use App\Enums\LanguageCode;
use App\Models\Church;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('to array', function (): void {
    $church = Church::query()->createQuietly([
        'id' => 1,
        'name' => 'Test Church',
        'locale' => LanguageCode::ENGLISH->value,
        'active' => true,
        'domain' => 'test.localhost',
    ])->fresh();

    expect(array_keys($church->toArray()))->toBe([
        'id',
        'name',
        'locale',
        'active',
        'features',
        'domain',
        'data',
        'created_at',
        'updated_at',
        'media',
    ]);
});

test('casts are correct', function (): void {
    $church = Church::query()->createQuietly([
        'id' => 1,
        'name' => 'Test Church',
        'locale' => LanguageCode::ENGLISH->value,
        'active' => true,
        'domain' => 'test.localhost',
    ])->fresh();

    expect($church->active)->toBeBool();
    expect($church->features)->toBeInstanceOf(TenantFeatures::class);
    expect($church->features->toArray())->toBe(['books' => false]);
});

describe('features cast', function (): void {
    it('stores tenant features from an array', function (): void {
        $church = Church::query()->createQuietly([
            'id' => 1,
            'name' => 'Test Church',
            'locale' => LanguageCode::ENGLISH->value,
            'active' => true,
            'features' => ['books' => true],
            'domain' => 'test.localhost',
        ])->fresh();

        expect($church->features)->toBeInstanceOf(TenantFeatures::class);
        expect($church->features->books)->toBeTrue();
    });

    it('falls back to defaults for invalid stored values', function (): void {
        $church = new Church;
        $church->setRawAttributes(['features' => 'invalid-json']);

        expect($church->features)->toBeInstanceOf(TenantFeatures::class);
        expect($church->features->toArray())->toBe(['books' => false]);
    });
});
