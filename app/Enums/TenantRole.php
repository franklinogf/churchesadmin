<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum TenantRole: string
{
    use EnumToArray, HasOptions;

    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case SECRETARY = 'secretary';
    case NO_ROLE = 'no_role';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => __('Super Admin'),
            self::ADMIN => __('Admin'),
            self::SECRETARY => __('Secretary'),
            self::NO_ROLE => __('No Role'),
        };
    }
}
