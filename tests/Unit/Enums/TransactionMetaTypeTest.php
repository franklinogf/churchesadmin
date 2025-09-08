<?php

declare(strict_types=1);

use App\Enums\TransactionMetaType;

it('has needed enums', function (): void {
    expect(TransactionMetaType::cases())->toHaveCount(4);
    expect(TransactionMetaType::INITIAL->value)->toBe('initial');
    expect(TransactionMetaType::CHECK->value)->toBe('check');
    expect(TransactionMetaType::OFFERING->value)->toBe('offering');
    expect(TransactionMetaType::EXPENSE->value)->toBe('expense');
});

test('label return correct label', function (): void {
    expect(TransactionMetaType::INITIAL->label())->toBe(__('enum.transaction_meta_type.initial'))->toBeString();
    expect(TransactionMetaType::CHECK->label())->toBe(__('enum.transaction_meta_type.check'))->toBeString();
    expect(TransactionMetaType::OFFERING->label())->toBe(__('enum.transaction_meta_type.offering'))->toBeString();
    expect(TransactionMetaType::EXPENSE->label())->toBe(__('enum.transaction_meta_type.expense'))->toBeString();
});
