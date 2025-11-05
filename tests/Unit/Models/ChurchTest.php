<?php

declare(strict_types=1);

use App\Enums\LanguageCode;
use App\Models\Church;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('to array', function (): void {
    $church = Church::createQuietly([
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
        'domain',
        'data',
        'created_at',
        'updated_at',
        'media',
    ]);
});

test('casts are correct', function (): void {
    $church = Church::createQuietly([
        'id' => 1,
        'name' => 'Test Church',
        'locale' => LanguageCode::ENGLISH->value,
        'active' => true,
        'domain' => 'test.localhost',
    ])->fresh();

    expect($church->active)->toBeBool();

});
