<?php

declare(strict_types=1);

namespace App\Enums;

enum ModelMorphName: string
{
    case MEMBER = 'member';
    case MISSIONARY = 'missionary';
    case USER = 'user';
    case CHURCH = 'church';
    case CHURCH_WALLET = 'church_wallet';
    case OFFERING_TYPE = 'offering_type';
    case CHECK_LAYOUT = 'check_layout';
    case EMAIL = 'email';
}
