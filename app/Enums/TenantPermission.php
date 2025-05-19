<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum TenantPermission: string
{
    use EnumToArray, HasOptions;

    case REGULAR_TAG_UPDATE = 'regular.tag.update';
    case REGULAR_TAG_DELETE = 'regular.tag.delete';
    case REGULAR_TAG_CREATE = 'regular.tag.create';
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
    case CHECK_LAYOUTS_MANAGE = 'check.layouts.manage';
    case CHECK_LAYOUTS_CREATE = 'check.layouts.create';
    case CHECK_LAYOUTS_UPDATE = 'check.layouts.update';
    case CHECK_LAYOUTS_DELETE = 'check.layouts.delete';

    public function label(): string
    {
        return match ($this) {
            self::REGULAR_TAG_UPDATE => __('Update :model', ['model' => __('Regular tags')]),
            self::REGULAR_TAG_DELETE => __('Delete :model', ['model' => __('Regular tags')]),
            self::REGULAR_TAG_CREATE => __('Create :model', ['model' => __('Regular tags')]),
            self::USERS_MANAGE => __('Manage :model', ['model' => __('Users')]),
            self::USERS_CREATE => __('Create :model', ['model' => __('User')]),
            self::USERS_UPDATE => __('Update :model', ['model' => __('User')]),
            self::USERS_DELETE => __('Delete :model', ['model' => __('User')]),
            self::SKILLS_MANAGE => __('Manage :model', ['model' => __('Skills')]),
            self::SKILLS_CREATE => __('Create :model', ['model' => __('Skill')]),
            self::SKILLS_UPDATE => __('Update :model', ['model' => __('Skill')]),
            self::SKILLS_DELETE => __('Delete :model', ['model' => __('Skill')]),
            self::CATEGORIES_MANAGE => __('Manage :model', ['model' => __('Categories')]),
            self::CATEGORIES_CREATE => __('Create :model', ['model' => __('Category')]),
            self::CATEGORIES_UPDATE => __('Update :model', ['model' => __('Category')]),
            self::CATEGORIES_DELETE => __('Delete :model', ['model' => __('Category')]),
            self::MEMBERS_MANAGE => __('Manage :model', ['model' => __('Members')]),
            self::MEMBERS_CREATE => __('Create :model', ['model' => __('Member')]),
            self::MEMBERS_UPDATE => __('Update :model', ['model' => __('Member')]),
            self::MEMBERS_DELETE => __('Delete :model', ['model' => __('Member')]),
            self::MEMBERS_FORCE_DELETE => __('Force Delete Member'),
            self::MEMBERS_RESTORE => __('Restore Member'),
            self::MISSIONARIES_MANAGE => __('Manage :model', ['model' => __('Missionaries')]),
            self::MISSIONARIES_CREATE => __('Create :model', ['model' => __('Missionary')]),
            self::MISSIONARIES_UPDATE => __('Update :model', ['model' => __('Missionary')]),
            self::MISSIONARIES_DELETE => __('Delete :model', ['model' => __('Missionary')]),
            self::MISSIONARIES_FORCE_DELETE => __('Force Delete Missionary'),
            self::MISSIONARIES_RESTORE => __('Restore Missionary'),
            self::OFFERINGS_MANAGE => __('Manage :model', ['model' => __('Offerings')]),
            self::OFFERINGS_CREATE => __('Create :model', ['model' => __('Offering')]),
            self::OFFERINGS_UPDATE => __('Update :model', ['model' => __('Offering')]),
            self::OFFERINGS_DELETE => __('Delete :model', ['model' => __('Offering')]),
            self::OFFERING_TYPES_MANAGE => __('Manage :model', ['model' => __('Offering types')]),
            self::OFFERING_TYPES_CREATE => __('Create :model', ['model' => __('Offering type')]),
            self::OFFERING_TYPES_UPDATE => __('Update :model', ['model' => __('Offering type')]),
            self::OFFERING_TYPES_DELETE => __('Delete :model', ['model' => __('Offering type')]),
            self::EXPENSE_TYPES_MANAGE => __('Manage Expense Types'),
            self::EXPENSE_TYPES_CREATE => __('Create Expense Type'),
            self::EXPENSE_TYPES_UPDATE => __('Update Expense Type'),
            self::EXPENSE_TYPES_DELETE => __('Delete Expense Type'),
            self::WALLETS_MANAGE => __('Manage Wallets'),
            self::WALLETS_CREATE => __('Create Wallet'),
            self::WALLETS_UPDATE => __('Update Wallet'),
            self::WALLETS_DELETE => __('Delete Wallet'),
            self::CHECK_LAYOUTS_MANAGE => __('Manage Check Layouts'),
            self::CHECK_LAYOUTS_CREATE => __('Create Check Layout'),
            self::CHECK_LAYOUTS_UPDATE => __('Update Check Layout'),
            self::CHECK_LAYOUTS_DELETE => __('Delete Check Layout'),
        };
    }
}
