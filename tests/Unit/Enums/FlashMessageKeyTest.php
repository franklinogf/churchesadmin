<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;

it('has needed enums', function (): void {
    expect(FlashMessageKey::cases())->toHaveCount(3);
    expect(FlashMessageKey::SUCCESS->value)->toBe('success');
    expect(FlashMessageKey::ERROR->value)->toBe('error');
    expect(FlashMessageKey::MESSAGE->value)->toBe('message');
});

test('names returns correct array', function (): void {
    expect(FlashMessageKey::names())->toBe([
        'SUCCESS',
        'ERROR',
        'MESSAGE',
    ]);
});

test('values returns correct array', function (): void {
    expect(FlashMessageKey::values())->toBe([
        'success',
        'error',
        'message',
    ]);
});
