<?php

declare(strict_types=1);

use App\Enums\EmailStatus;

it('has needed enums', function (): void {
    expect(EmailStatus::cases())->toHaveCount(4);
    expect(EmailStatus::SENT->value)->toBe('sent');
    expect(EmailStatus::FAILED->value)->toBe('failed');
    expect(EmailStatus::PENDING->value)->toBe('pending');
    expect(EmailStatus::SENDING->value)->toBe('sending');
});

test('label return correct label', function (): void {
    expect(EmailStatus::SENT->label())->toBe(__('enum.email_status.sent'))->toBeString();
    expect(EmailStatus::FAILED->label())->toBe(__('enum.email_status.failed'))->toBeString();
    expect(EmailStatus::PENDING->label())->toBe(__('enum.email_status.pending'))->toBeString();
    expect(EmailStatus::SENDING->label())->toBe(__('enum.email_status.sending'))->toBeString();
});
