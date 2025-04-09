<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum TenantPermissionName: string
{
    use EnumToArray;
    case UPDATE_REGULAR_TAG = 'update_regular_tag';
    case DELETE_REGULAR_TAG = 'delete_regular_tag';
    case CREATE_REGULAR_TAG = 'create_regular_tag';
    case MANAGE_USERS = 'manage_users';
    case CREATE_USERS = 'create_users';
    case UPDATE_USERS = 'update_users';
    case DELETE_USERS = 'delete_users';
    case MANAGE_SKILLS = 'manage_skills';
    case CREATE_SKILLS = 'create_skills';
    case UPDATE_SKILLS = 'update_skills';
    case DELETE_SKILLS = 'delete_skills';
    case MANAGE_CATEGORIES = 'manage_categories';
    case CREATE_CATEGORIES = 'create_categories';
    case UPDATE_CATEGORIES = 'update_categories';
    case DELETE_CATEGORIES = 'delete_categories';
    case MANAGE_MEMBERS = 'manage_members';
    case CREATE_MEMBERS = 'create_members';
    case UPDATE_MEMBERS = 'update_members';
    case DELETE_MEMBERS = 'delete_members';
    case FORCE_DELETE_MEMBERS = 'force_delete_members';
    case RESTORE_MEMBERS = 'restore_members';
    case MANAGE_MISSIONARIES = 'manage_missionaries';
    case CREATE_MISSIONARIES = 'create_missionaries';
    case UPDATE_MISSIONARIES = 'update_missionaries';
    case DELETE_MISSIONARIES = 'delete_missionaries';
    case FORCE_DELETE_MISSIONARIES = 'force_delete_missionaries';
    case RESTORE_MISSIONARIES = 'restore_missionaries';

}
