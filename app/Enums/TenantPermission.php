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
            self::UPDATE_REGULAR_TAG => __('Update Regular Tag'),
            self::DELETE_REGULAR_TAG => __('Delete Regular Tag'),
            self::CREATE_REGULAR_TAG => __('Create Regular Tag'),
            self::MANAGE_USERS => __('Manage Users'),
            self::CREATE_USERS => __('Create User'),
            self::UPDATE_USERS => __('Update User'),
            self::DELETE_USERS => __('Delete User'),
            self::MANAGE_SKILLS => __('Manage Skills'),
            self::CREATE_SKILLS => __('Create Skill'),
            self::UPDATE_SKILLS => __('Update Skill'),
            self::DELETE_SKILLS => __('Delete Skill'),
            self::MANAGE_CATEGORIES => __('Manage Categories'),
            self::CREATE_CATEGORIES => __('Create Category'),
            self::UPDATE_CATEGORIES => __('Update Category'),
            self::DELETE_CATEGORIES => __('Delete Category'),
            self::MANAGE_MEMBERS => __('Manage Members'),
            self::CREATE_MEMBERS => __('Create Member'),
            self::UPDATE_MEMBERS => __('Update Member'),
            self::DELETE_MEMBERS => __('Delete Member'),
            self::FORCE_DELETE_MEMBERS => __('Force Delete Member'),
            self::RESTORE_MEMBERS => __('Restore Member'),
            self::MANAGE_MISSIONARIES => __('Manage Missionaries'),
            self::CREATE_MISSIONARIES => __('Create Missionary'),
            self::UPDATE_MISSIONARIES => __('Update Missionary'),
            self::DELETE_MISSIONARIES => __('Delete Missionary'),
            self::FORCE_DELETE_MISSIONARIES => __('Force Delete Missionary'),
            self::RESTORE_MISSIONARIES => __('Restore Missionary'),
            self::MANAGE_OFFERINGS => __('Manage Offerings'),
            self::CREATE_OFFERINGS => __('Create Offering'),
            self::UPDATE_OFFERINGS => __('Update Offering'),
            self::DELETE_OFFERINGS => __('Delete Offering'),
            self::MANAGE_OFFERING_TYPES => __('Manage Offering Types'),
            self::CREATE_OFFERING_TYPES => __('Create Offering Type'),
            self::UPDATE_OFFERING_TYPES => __('Update Offering Type'),
            self::DELETE_OFFERING_TYPES => __('Delete Offering Type'),

        };
    }
}
