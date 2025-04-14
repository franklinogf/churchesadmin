<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    /**
     * Determine whether the user can view models.
     */
    public function viewAny(User $user): Response
    {
        if ($user->can(TenantPermission::MANAGE_USERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Users')]));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->can(TenantPermission::CREATE_USERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Users')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): Response
    {
        if ($user->can(TenantPermission::UPDATE_USERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Users')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        if ($user->can(TenantPermission::DELETE_USERS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Users')]));
    }
}
