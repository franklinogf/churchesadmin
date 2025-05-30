<?php

declare(strict_types=1);

use App\Enums\LanguageCode;

it('has needed enums', function (): void {

    expect(LanguageCode::names())->toBe([
        'ENGLISH',
        'SPANISH',
    ]);

});

test('label return correct label', function (): void {

    expect(LanguageCode::ENGLISH->label())->toBe(__('enum.language_code.en'))->toBeString()
        ->and(LanguageCode::SPANISH->label())->toBe(__('enum.language_code.es'))->toBeString();

});

test('options return an array', function (): void {

    expect(LanguageCode::options())
        ->toBeArray()
        ->toHaveCount(2)
        ->toBe([
            [
                'value' => 'en',
                'label' => __('enum.language_code.en'),
            ],
            [
                'value' => 'es',
                'label' => __('enum.language_code.es'),
            ],
        ]);

});

test('label for filament returns correct label', function (): void {

    expect(LanguageCode::ENGLISH->getLabel())
        ->toBe(__('enum.language_code.en'))
        ->toBeString()
        ->and(LanguageCode::SPANISH->getLabel())
        ->toBe(__('enum.language_code.es'))
        ->toBeString();

});

test('color for filament returns correct color', function (): void {

    expect(LanguageCode::ENGLISH->getColor())
        ->toBeArray()
        ->and(LanguageCode::SPANISH->getColor())
        ->toBeArray();

});
