<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class OfferingPolicy
{
    /**
     * Determine whether the user can view models.
     */
    public function viewAny(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::MANAGE_OFFERINGS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Offerings')]));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CREATE_OFFERINGS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Offerings')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::UPDATE_OFFERINGS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Offerings')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::DELETE_OFFERINGS)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Offerings')]));
    }
}
