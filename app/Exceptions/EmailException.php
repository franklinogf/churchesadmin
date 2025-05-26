<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class EmailException extends Exception
{
    public static function invalidRecipientType(): self
    {
        return new self(__('flash.message.email.invalid_recipient_type'), 422);
    }

    public static function noRecipientsSelected(): self
    {
        return new self(__('flash.message.email.no_recipients_selected'), 422);
    }

    public static function unknownError(): self
    {
        return new self(__('flash.message.email.unknown_error'), 500);
    }
}
