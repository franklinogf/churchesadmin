<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum TenantRoleName: string
{
    use EnumToArray;
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case SECRETARY = 'secretary';
}
