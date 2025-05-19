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
        if ($user->can(TenantPermission::MISSIONARIES_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Missionaries')]));
    }

    public function create(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::MISSIONARIES_CREATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Missionaries')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::MISSIONARIES_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Missionaries')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::MISSIONARIES_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Missionaries')]));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::MISSIONARIES_RESTORE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.restore', ['label' => __('Missionaries')]));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::MISSIONARIES_FORCE_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.force_delete', ['label' => __('Missionaries')]));
    }
}
