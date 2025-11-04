<?php

declare(strict_types=1);

use App\Enums\SessionName;

it('has needed enums', function (): void {
    expect(SessionName::cases())->toHaveCount(2);
    expect(SessionName::EMAIL_RECIPIENTS->value)->toBe('email_recipients');
    expect(SessionName::CONTRIBUTIONS_REPORT_YEAR->value)->toBe('contributions_report_year');
});
