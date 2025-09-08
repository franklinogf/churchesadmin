<?php

declare(strict_types=1);

use App\Enums\SessionName;

it('has needed enums', function (): void {
    expect(SessionName::cases())->toHaveCount(1);
    expect(SessionName::EMAIL_RECIPIENTS->value)->toBe('email_recipients');
});
