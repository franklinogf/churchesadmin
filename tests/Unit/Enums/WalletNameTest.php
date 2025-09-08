<?php

declare(strict_types=1);

use App\Enums\WalletName;

it('has needed enums', function (): void {
    expect(WalletName::cases())->toHaveCount(1);
    expect(WalletName::PRIMARY->value)->toBe('primary');
});

test('label return correct label', function (): void {
    expect(WalletName::PRIMARY->label())->toBe(__('enum.wallet_name.primary'))->toBeString();
});
