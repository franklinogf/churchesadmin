<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class CheckLayoutPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CHECK_LAYOUTS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Check layouts')]));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CHECK_LAYOUTS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view', ['label' => __('Check layouts')]));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CHECK_LAYOUTS_CREATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Check layouts')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CHECK_LAYOUTS_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Check layouts')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CHECK_LAYOUTS_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Check layouts')]));
    }
}
