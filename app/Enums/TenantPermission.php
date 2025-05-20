<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum TenantPermission: string
{
    use EnumToArray, HasOptions;

    case REGULAR_TAGS_UPDATE = 'regular.tags.update';
    case REGULAR_TAGS_DELETE = 'regular.tags.delete';
    case REGULAR_TAGS_CREATE = 'regular.tags.create';
    case USERS_MANAGE = 'users.manage';
    case USERS_CREATE = 'users.create';
    case USERS_UPDATE = 'users.update';
    case USERS_DELETE = 'users.delete';
    case SKILLS_MANAGE = 'skills.manage';
    case SKILLS_CREATE = 'skills.create';
    case SKILLS_UPDATE = 'skills.update';
    case SKILLS_DELETE = 'skills.delete';
    case CATEGORIES_MANAGE = 'categories.manage';
    case CATEGORIES_CREATE = 'categories.create';
    case CATEGORIES_UPDATE = 'categories.update';
    case CATEGORIES_DELETE = 'categories.delete';
    case MEMBERS_MANAGE = 'members.manage';
    case MEMBERS_CREATE = 'members.create';
    case MEMBERS_UPDATE = 'members.update';
    case MEMBERS_DELETE = 'members.delete';
    case MEMBERS_FORCE_DELETE = 'members.force.delete';
    case MEMBERS_RESTORE = 'members.restore';
    case MISSIONARIES_MANAGE = 'missionaries.manage';
    case MISSIONARIES_CREATE = 'missionaries.create';
    case MISSIONARIES_UPDATE = 'missionaries.update';
    case MISSIONARIES_DELETE = 'missionaries.delete';
    case MISSIONARIES_FORCE_DELETE = 'missionaries.force.delete';
    case MISSIONARIES_RESTORE = 'missionaries.restore';
    case OFFERINGS_MANAGE = 'offerings.manage';
    case OFFERINGS_CREATE = 'offerings.create';
    case OFFERINGS_UPDATE = 'offerings.update';
    case OFFERINGS_DELETE = 'offerings.delete';
    case OFFERING_TYPES_MANAGE = 'offering.types.manage';
    case OFFERING_TYPES_CREATE = 'offering.types.create';
    case OFFERING_TYPES_UPDATE = 'offering.types.update';
    case OFFERING_TYPES_DELETE = 'offering.types.delete';
    case EXPENSE_TYPES_MANAGE = 'expense.types.manage';
    case EXPENSE_TYPES_CREATE = 'expense.types.create';
    case EXPENSE_TYPES_UPDATE = 'expense.types.update';
    case EXPENSE_TYPES_DELETE = 'expense.types.delete';
    case WALLETS_MANAGE = 'wallets.manage';
    case WALLETS_CREATE = 'wallets.create';
    case WALLETS_UPDATE = 'wallets.update';
    case WALLETS_DELETE = 'wallets.delete';
    case WALLETS_CHECK_LAYOUT_UPDATE = 'wallets.check.layout.update';
    case CHECK_LAYOUTS_MANAGE = 'check.layouts.manage';
    case CHECK_LAYOUTS_CREATE = 'check.layouts.create';
    case CHECK_LAYOUTS_UPDATE = 'check.layouts.update';
    case CHECK_LAYOUTS_DELETE = 'check.layouts.delete';
    case CHECKS_MANAGE = 'checks.manage';
    case CHECKS_CREATE = 'checks.create';
    case CHECKS_UPDATE = 'checks.update';
    case CHECKS_DELETE = 'checks.delete';
    case CHECKS_CONFIRM = 'checks.confirm';
    case CHECKS_PRINT = 'checks.print';

    public function label(): string
    {
        return __("enum.tenant_permission.{$this->value}");
    }
}
