<?php

declare(strict_types=1);

namespace App\Enums;

enum EmailStatus: string
{
    case SENT = 'sent';
    case FAILED = 'failed';
    case PENDING = 'pending';
}
