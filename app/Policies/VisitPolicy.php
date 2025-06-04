<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class VisitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(TenantUser $tenantUser): Response
    {
        if ($tenantUser->can(TenantPermission::VISITS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Visit')]));
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
    public function create(TenantUser $tenantUser): Response
    {
        if ($tenantUser->can(TenantPermission::VISITS_CREATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Visit')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $tenantUser): Response
    {
        if ($tenantUser->can(TenantPermission::VISITS_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Visit')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $tenantUser): Response
    {
        if ($tenantUser->can(TenantPermission::VISITS_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Visit')]));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(TenantUser $tenantUser): Response
    {
        if ($tenantUser->can(TenantPermission::VISITS_RESTORE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.restore', ['label' => __('Visit')]));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(TenantUser $tenantUser): Response
    {
        if ($tenantUser->can(TenantPermission::VISITS_FORCE_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.force_delete', ['label' => __('Visit')]));
    }
}
