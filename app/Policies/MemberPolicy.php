<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class MemberPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->can(TenantPermission::CREATE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Members')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): Response
    {
        if ($user->can(TenantPermission::UPDATE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Members')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        if ($user->can(TenantPermission::DELETE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Members')]));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): Response
    {
        if ($user->can(TenantPermission::RESTORE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.restore', ['label' => __('Members')]));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): Response
    {
        if ($user->can(TenantPermission::FORCE_DELETE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.force_delete', ['label' => __('Members')]));
    }
}
