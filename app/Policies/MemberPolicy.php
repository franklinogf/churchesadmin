<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermissionName;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class MemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(): bool
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

        return Response::deny(__('permission.create', ['label' => __('Members')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): Response
    {
        if ($user->can(TenantPermissionName::UPDATE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Members')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        if ($user->can(TenantPermissionName::DELETE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Members')]));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): Response
    {
        if ($user->can(TenantPermissionName::RESTORE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.restore', ['label' => __('Members')]));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): Response
    {
        if ($user->can(TenantPermissionName::FORCE_DELETE_MEMBERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.force_delete', ['label' => __('Members')]));
    }
}
