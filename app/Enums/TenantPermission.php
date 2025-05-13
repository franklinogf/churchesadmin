<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum TenantPermission: string
{
    use EnumToArray, HasOptions;
    case UPDATE_REGULAR_TAG = 'regular_tag_update';
    case DELETE_REGULAR_TAG = 'regular_tag_delete';
    case CREATE_REGULAR_TAG = 'regular_tag_create';
    case MANAGE_USERS = 'users_manage';
    case CREATE_USERS = 'users_create';
    case UPDATE_USERS = 'users_update';
    case DELETE_USERS = 'users_delete';
    case MANAGE_SKILLS = 'skills_manage';
    case CREATE_SKILLS = 'skills_create';
    case UPDATE_SKILLS = 'skills_update';
    case DELETE_SKILLS = 'skills_delete';
    case MANAGE_CATEGORIES = 'categories_manage';
    case CREATE_CATEGORIES = 'categories_create';
    case UPDATE_CATEGORIES = 'categories_update';
    case DELETE_CATEGORIES = 'categories_delete';
    case MANAGE_MEMBERS = 'members_manage';
    case CREATE_MEMBERS = 'members_create';
    case UPDATE_MEMBERS = 'members_update';
    case DELETE_MEMBERS = 'members_delete';
    case FORCE_DELETE_MEMBERS = 'members_force_delete';
    case RESTORE_MEMBERS = 'members_restore';
    case MANAGE_MISSIONARIES = 'missionaries_manage';
    case CREATE_MISSIONARIES = 'missionaries_create';
    case UPDATE_MISSIONARIES = 'missionaries_update';
    case DELETE_MISSIONARIES = 'missionaries_delete';
    case FORCE_DELETE_MISSIONARIES = 'missionaries_force_delete';
    case RESTORE_MISSIONARIES = 'missionaries_restore';
    case MANAGE_OFFERINGS = 'offerings_manage';
    case CREATE_OFFERINGS = 'offerings_create';
    case UPDATE_OFFERINGS = 'offerings_update';
    case DELETE_OFFERINGS = 'offerings_delete';
    case MANAGE_OFFERING_TYPES = 'offering_types_manage';
    case CREATE_OFFERING_TYPES = 'offering_types_create';
    case UPDATE_OFFERING_TYPES = 'offering_types_update';
    case DELETE_OFFERING_TYPES = 'offering_types_delete';

    public function label(): string
    {
        return match ($this) {
            self::UPDATE_REGULAR_TAG => __('Update :model',{model:('Regular Tag')}),
            self::DELETE_REGULAR_TAG => __('Delete :model',{model:('Regular Tag')}),
            self::CREATE_REGULAR_TAG => __('Create :model',{model:('Regular Tag')}),
            self::MANAGE_USERS => __('Manage :model',{model:('Users')}),
            self::CREATE_USERS => __('Create :model',{model:('User')}),
            self::UPDATE_USERS => __('Update :model',{model:('User')}),
            self::DELETE_USERS => __('Delete :model',{model:('User')}),
            self::MANAGE_SKILLS => __('Manage :model',{model:('Skills')}),
            self::CREATE_SKILLS => __('Create :model',{model:('Skill')}),
            self::UPDATE_SKILLS => __('Update :model',{model:('Skill')}),
            self::DELETE_SKILLS => __('Delete :model',{model:('Skill')}),
            self::MANAGE_CATEGORIES => __('Manage :model',{model:('Categories')}),
            self::CREATE_CATEGORIES => __('Create :model',{model:('Category')}),
            self::UPDATE_CATEGORIES => __('Update :model',{model:('Category')}),
            self::DELETE_CATEGORIES => __('Delete :model',{model:('Category')}),
            self::MANAGE_MEMBERS => __('Manage :model',{model:('Members')}),
            self::CREATE_MEMBERS => __('Create :model',{model:('Member')}),
            self::UPDATE_MEMBERS => __('Update :model',{model:('Member')}),
            self::DELETE_MEMBERS => __('Delete :model',{model:('Member')}),
            self::FORCE_DELETE_MEMBERS => __('Force Delete Member'),
            self::RESTORE_MEMBERS => __('Restore Member'),
            self::MANAGE_MISSIONARIES => __('Manage :model',{model:('Missionaries')}),
            self::CREATE_MISSIONARIES => __('Create :model',{model:('Missionary')}),
            self::UPDATE_MISSIONARIES => __('Update :model',{model:('Missionary')}),
            self::DELETE_MISSIONARIES => __('Delete :model',{model:('Missionary')}),
            self::FORCE_DELETE_MISSIONARIES => __('Force Delete Missionary'),
            self::RESTORE_MISSIONARIES => __('Restore Missionary'),
            self::MANAGE_OFFERINGS => __('Manage :model',{model:('Offerings')}),
            self::CREATE_OFFERINGS => __('Create :model',{model:('Offering')}),
            self::UPDATE_OFFERINGS => __('Update :model',{model:('Offering')}),
            self::DELETE_OFFERINGS => __('Delete :model',{model:('Offering')}),
            self::MANAGE_OFFERING_TYPES => __('Manage :model',{model:('Offering Types')}),
            self::CREATE_OFFERING_TYPES => __('Create :model',{model:('Offering Type')}),
            self::UPDATE_OFFERING_TYPES => __('Update :model',{model:('Offering Type')}),
            self::DELETE_OFFERING_TYPES => __('Delete :model',{model:('Offering Type')}),

        };
    }
}
