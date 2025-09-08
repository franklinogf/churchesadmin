<?php

declare(strict_types=1);

namespace App\Enums;

enum EmailStatus: string
{
    case SENT = 'sent';
    case FAILED = 'failed';
    case PENDING = 'pending';
    case SENDING = 'sending';

    public function label(): string
    {
        return match ($this) {
            self::SENT => __('enum.email_status.sent'),
            self::FAILED => __('enum.email_status.failed'),
            self::PENDING => __('enum.email_status.pending'),
            self::SENDING => __('enum.email_status.sending'),
        };
    }
}
