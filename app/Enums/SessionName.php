<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionName: string
{
    case EMAIL_MEMBERS_IDS = 'email_members_ids';
    case EMAIL_MISSIONARIES_IDS = 'email_missionaries_ids';
}
