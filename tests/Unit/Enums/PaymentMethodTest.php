<?php

declare(strict_types=1);

use App\Enums\PaymentMethod;

it('has needed enums', function (): void {
    expect(PaymentMethod::names())->toBe([
        'CASH',
        'CHECK',
    ]);
});

test('label return correct label', function (): void {
    expect(PaymentMethod::CASH->label())->toBe(__('enum.payment_method.cash'))->toBeString();
    expect(PaymentMethod::CHECK->label())->toBe(__('enum.payment_method.check'))->toBeString();
});

test('options return an array', function (): void {
    expect(PaymentMethod::options())->toBeArray();
    expect(PaymentMethod::options())->toHaveCount(2);

    expect(PaymentMethod::options())->toEqual([
        [
            'value' => 'cash',
            'label' => __('enum.payment_method.cash'),
        ],
        [
            'value' => 'check',
            'label' => __('enum.payment_method.check'),
        ],
    ]);
});
