<?php

declare(strict_types=1);

use App\Enums\TransactionType;

it('has needed enums', function (): void {
    expect(TransactionType::cases())->toHaveCount(3);
    expect(TransactionType::DEPOSIT->value)->toBe('deposit');
    expect(TransactionType::WITHDRAW->value)->toBe('withdraw');
    expect(TransactionType::PREVIOUS->value)->toBe('previous_balance');
});

test('label return correct label', function (): void {
    expect(TransactionType::DEPOSIT->label())->toBe(__('enum.transaction_type.deposit'))->toBeString();
    expect(TransactionType::WITHDRAW->label())->toBe(__('enum.transaction_type.withdraw'))->toBeString();
    expect(TransactionType::PREVIOUS->label())->toBe(__('enum.transaction_type.previous_balance'))->toBeString();
});
