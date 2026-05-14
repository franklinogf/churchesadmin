<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class ChurchWalletPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::WALLETS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Wallets')]));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::WALLETS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view', ['label' => __('Wallets')]));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::WALLETS_CREATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Wallets')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::WALLETS_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Wallets')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::WALLETS_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Wallets')]));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::WALLETS_RESTORE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.restore', ['label' => __('Wallets')]));
    }

    /**
     * Determine whether the user can update the check layout.
     */
    public function updateCheckLayout(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::WALLETS_CHECK_LAYOUT_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Wallet').' '.__('Check Layout')]));
    }
}
