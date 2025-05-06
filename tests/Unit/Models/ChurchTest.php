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
    ])->fresh();

    expect(array_keys($church->toArray()))->toBe([
        'id',
        'name',
        'locale',
        'active',
        'data',
        'created_at',
        'updated_at',
    ]);
});

test('casts are correct', function (): void {
    $church = Church::createQuietly([
        'id' => 1,
        'name' => 'Test Church',
        'locale' => LanguageCode::ENGLISH->value,
        'active' => true,
    ])->fresh();

    expect($church->active)->toBeBool();

});
