<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum TenantPermissionName: string
{
    use EnumToArray;
    case UPDATE_REGULAR_TAG = 'update_regular_tag';
    case DELETE_REGULAR_TAG = 'delete_regular_tag';
    case CREATE_USERS = 'create_users';
    case UPDATE_USERS = 'update_users';
    case DELETE_USERS = 'delete_users';
    case CREATE_SKILLS = 'create_skills';
    case UPDATE_SKILLS = 'update_skills';
    case DELETE_SKILLS = 'delete_skills';
    case CREATE_CATEGORIES = 'create_categories';
    case UPDATE_CATEGORIES = 'update_categories';
    case DELETE_CATEGORIES = 'delete_categories';
    case CREATE_MEMBERS = 'create_members';
    case UPDATE_MEMBERS = 'update_members';
    case DELETE_MEMBERS = 'delete_members';
    case CREATE_MISSIONARIES = 'create_missionaries';
    case UPDATE_MISSIONARIES = 'update_missionaries';
    case DELETE_MISSIONARIES = 'delete_missionaries';

}
