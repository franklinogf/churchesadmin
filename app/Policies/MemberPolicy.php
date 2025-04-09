<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermissionName;
use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class MemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Member $member): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->can(TenantPermissionName::CREATE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to create members.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Member $member): Response
    {
        if ($user->can(TenantPermissionName::UPDATE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this member.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Member $member): Response
    {
        if ($user->can(TenantPermissionName::DELETE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this member.');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Member $member): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Member $member): bool
    {
        return false;
    }
}
