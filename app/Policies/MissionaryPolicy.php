<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class MissionaryPolicy
{
    /**
     * Determine whether the user can view models.
     */
    public function viewAny(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::MANAGE_MISSIONARIES)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Missionaries')]));
    }

    public function create(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CREATE_MISSIONARIES)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Missionaries')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::UPDATE_MISSIONARIES)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Missionaries')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::DELETE_MISSIONARIES)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Missionaries')]));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::RESTORE_MISSIONARIES)) {
            return Response::allow();
        }

        return Response::deny(__('permission.restore', ['label' => __('Missionaries')]));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::FORCE_DELETE_MISSIONARIES)) {
            return Response::allow();
        }

        return Response::deny(__('permission.force_delete', ['label' => __('Missionaries')]));
    }
}
