<?php

declare(strict_types=1);

use App\Enums\CheckType;

it('has needed enums', function (): void {
    expect(CheckType::names())->toBe([
        'PAYMENT',
        'REFUND',
    ]);
});

test('label return correct label', function (): void {
    expect(CheckType::PAYMENT->label())->toBe(__('enum.check_type.payment'))->toBeString();
    expect(CheckType::REFUND->label())->toBe(__('enum.check_type.refund'))->toBeString();
});

test('options return an array', function (): void {
    expect(CheckType::options())->toBeArray();
    expect(CheckType::options())->toHaveCount(2);

    expect(CheckType::options())->toEqual([
        [
            'value' => 'payment',
            'label' => __('enum.check_type.payment'),
        ],
        [
            'value' => 'refund',
            'label' => __('enum.check_type.refund'),
        ],
    ]);
});
