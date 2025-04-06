<?php

declare(strict_types=1);

use App\Enums\Gender;

it('has needed enums', function (): void {

    expect(Gender::names())->toBe([
        'MALE',
        'FEMALE',
    ]);

});

test('label return correct label', function (): void {

    expect(Gender::MALE->label())->toBe(__('Male'))->toBeString();
    expect(Gender::FEMALE->label())->toBe(__('Female'))->toBeString();

});

test('options return an array', function (): void {

    expect(Gender::options())->toBeArray();
    expect(Gender::options())->toHaveCount(2);
    expect(Gender::options())->toHaveKeys([
        'male',
        'female',
    ]);

});
