<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum TenantPermission: string
{
    use EnumToArray, HasOptions;

    case REGULAR_TAGS_UPDATE = 'regular_tags.update';
    case REGULAR_TAGS_DELETE = 'regular_tags.delete';
    case REGULAR_TAGS_CREATE = 'regular_tags.create';
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
    case MEMBERS_FORCE_DELETE = 'members.force_delete';
    case MEMBERS_RESTORE = 'members.restore';
    case MEMBERS_DEACTIVATE = 'members.deactivate';
    case MEMBERS_ACTIVATE = 'members.activate';
    case MISSIONARIES_MANAGE = 'missionaries.manage';
    case MISSIONARIES_CREATE = 'missionaries.create';
    case MISSIONARIES_UPDATE = 'missionaries.update';
    case MISSIONARIES_DELETE = 'missionaries.delete';
    case MISSIONARIES_FORCE_DELETE = 'missionaries.force_delete';
    case MISSIONARIES_RESTORE = 'missionaries.restore';
    case OFFERINGS_MANAGE = 'offerings.manage';
    case OFFERINGS_CREATE = 'offerings.create';
    case OFFERINGS_UPDATE = 'offerings.update';
    case OFFERINGS_DELETE = 'offerings.delete';
    case OFFERING_TYPES_MANAGE = 'offering_types.manage';
    case OFFERING_TYPES_CREATE = 'offering_types.create';
    case OFFERING_TYPES_UPDATE = 'offering_types.update';
    case OFFERING_TYPES_DELETE = 'offering_types.delete';
    case EXPENSE_TYPES_MANAGE = 'expense_types.manage';
    case EXPENSE_TYPES_CREATE = 'expense_types.create';
    case EXPENSE_TYPES_UPDATE = 'expense_types.update';
    case EXPENSE_TYPES_DELETE = 'expense_types.delete';
    case DEACTIVATION_CODES_MANAGE = 'deactivation_codes.manage';
    case DEACTIVATION_CODES_CREATE = 'deactivation_codes.create';
    case DEACTIVATION_CODES_UPDATE = 'deactivation_codes.update';
    case DEACTIVATION_CODES_DELETE = 'deactivation_codes.delete';
    case WALLETS_MANAGE = 'wallets.manage';
    case WALLETS_CREATE = 'wallets.create';
    case WALLETS_UPDATE = 'wallets.update';
    case WALLETS_DELETE = 'wallets.delete';
    case WALLETS_RESTORE = 'wallets.restore';
    case WALLETS_CHECK_LAYOUT_UPDATE = 'wallets.check_layout.update';
    case CHECK_LAYOUTS_MANAGE = 'check_layouts.manage';
    case CHECK_LAYOUTS_CREATE = 'check_layouts.create';
    case CHECK_LAYOUTS_UPDATE = 'check_layouts.update';
    case CHECK_LAYOUTS_DELETE = 'check_layouts.delete';
    case CHECKS_MANAGE = 'checks.manage';
    case CHECKS_CREATE = 'checks.create';
    case CHECKS_UPDATE = 'checks.update';
    case CHECKS_DELETE = 'checks.delete';
    case CHECKS_CONFIRM = 'checks.confirm';
    case CHECKS_PRINT = 'checks.print';
    case EMAILS_MANAGE = 'emails.manage';
    case EMAILS_CREATE = 'emails.create';
    case EMAILS_UPDATE = 'emails.update';
    case EMAILS_DELETE = 'emails.delete';
    case EMAILS_SEND = 'emails.send';
    case EMAILS_SEND_TO_MEMBERS = 'emails.send_to.members';
    case EMAILS_SEND_TO_MISSIONARIES = 'emails.send_to.missionaries';
    case EMAILS_SEND_TO_VISITORS = 'emails.send_to.visitors';
    case VISITS_MANAGE = 'visits.manage';
    case VISITS_CREATE = 'visits.create';
    case VISITS_UPDATE = 'visits.update';
    case VISITS_DELETE = 'visits.delete';
    case VISITS_FORCE_DELETE = 'visits.force_delete';
    case VISITS_RESTORE = 'visits.restore';
    case EXPENSES_MANAGE = 'expenses.manage';
    case EXPENSES_CREATE = 'expenses.create';
    case EXPENSES_UPDATE = 'expenses.update';
    case EXPENSES_DELETE = 'expenses.delete';
    case ACTIVITY_LOGS_MANAGE = 'activity_logs.manage';

    public function label(): string
    {
        return __("enum.tenant_permission.{$this->value}");
    }
}
