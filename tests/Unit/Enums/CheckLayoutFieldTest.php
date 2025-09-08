<?php

declare(strict_types=1);

use App\Enums\CheckLayoutField;

it('has needed enums', function (): void {
    expect(CheckLayoutField::cases())->toHaveCount(5);
    expect(CheckLayoutField::PAYEE->value)->toBe('payee');
    expect(CheckLayoutField::AMOUNT->value)->toBe('amount');
    expect(CheckLayoutField::DATE->value)->toBe('date');
    expect(CheckLayoutField::MEMO->value)->toBe('memo');
    expect(CheckLayoutField::AMOUNT_IN_WORDS->value)->toBe('amount_in_words');
});

test('initialLayout returns correct structure', function (): void {
    $layout = CheckLayoutField::initialLayout();

    expect($layout)->toBeArray();
    expect($layout)->toHaveCount(5);

    // Check structure for each field
    foreach (CheckLayoutField::cases() as $index => $field) {
        expect($layout[$field->value])->toHaveKey('position');
        expect($layout[$field->value]['position'])->toHaveKey('x');
        expect($layout[$field->value]['position'])->toHaveKey('y');
        expect($layout[$field->value]['position']['x'])->toBe(0);
        expect($layout[$field->value]['position']['y'])->toBe($index * 20);
    }
});
