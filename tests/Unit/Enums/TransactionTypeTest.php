<?php

declare(strict_types=1);

use App\Enums\TransactionType;

it('has needed enums', function (): void {
    expect(TransactionType::cases())->toHaveCount(2);
    expect(TransactionType::DEPOSIT->value)->toBe('deposit');
    expect(TransactionType::WITHDRAW->value)->toBe('withdraw');
});

test('label return correct label', function (): void {
    expect(TransactionType::DEPOSIT->label())->toBe(__('enum.transaction_type.deposit'))->toBeString();
    expect(TransactionType::WITHDRAW->label())->toBe(__('enum.transaction_type.withdraw'))->toBeString();
});
