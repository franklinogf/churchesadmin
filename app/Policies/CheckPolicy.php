<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\Check;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class CheckPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(TenantUser $tenantUser): Response
    {
        if ($tenantUser->can(TenantPermission::CHECKS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Checks')]));

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(TenantUser $tenantUser): Response
    {
        if ($tenantUser->can(TenantPermission::CHECKS_CREATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Checks')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $tenantUser, Check $check): Response
    {
        if ($check->isConfirmed()) {
            return Response::deny(__('permission.confirmed_check.update'));
        }

        if ($tenantUser->can(TenantPermission::CHECKS_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Checks')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $tenantUser, Check $check): Response
    {
        if ($check->isConfirmed()) {
            return Response::deny(__('permission.confirmed_check.delete'));
        }

        if ($tenantUser->can(TenantPermission::CHECKS_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Checks')]));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(TenantUser $tenantUser, Check $check): Response
    {
        if ($check->isConfirmed()) {
            return Response::deny(__('permission.confirmed_check.force_delete'));
        }

        return Response::deny(__('permission.force_delete', ['label' => __('Checks')]));
    }

    /**
     * Determine whether the user can confirm checks.
     */
    public function confirm(TenantUser $tenantUser, ?Check $check = null): Response
    {
        if ($check && $check->isConfirmed()) {
            return Response::deny(__('permission.confirmed_check.confirm'));
        }

        if ($tenantUser->can(TenantPermission::CHECKS_CONFIRM)) {
            return Response::allow();
        }

        return Response::deny(__('permission.confirm', ['label' => __('Checks')]));
    }

    public function print(TenantUser $tenantUser, Check $check): Response
    {
        if ($tenantUser->cannot(TenantPermission::CHECKS_PRINT)) {
            return Response::deny(__('permission.print', ['label' => __('Checks')]));
        }

        if (! $check->isConfirmed()) {
            return Response::deny(__('permission.unconfirmed_check.print', ['label' => __('Checks')]));
        }

        return Response::allow();
    }
}
