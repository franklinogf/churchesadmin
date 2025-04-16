<?php

declare(strict_types=1);

use App\Enums\LanguageCode;

it('has needed enums', function (): void {

    expect(LanguageCode::names())->toBe([
        'EN',
        'ES',
    ]);

});

test('label return correct label', function (): void {

    expect(LanguageCode::EN->label())->toBe(__('English'))->toBeString();
    expect(LanguageCode::ES->label())->toBe(__('Spanish'))->toBeString();

});

test('options return an array', function (): void {

    expect(LanguageCode::options())->toBeArray();
    expect(LanguageCode::options())->toHaveCount(2);
    expect(LanguageCode::options())->toBe([
        [
            'value' => 'en',
            'label' => __('English'),
        ],
        [
            'value' => 'es',
            'label' => __('Spanish'),
        ],
    ]);

});
