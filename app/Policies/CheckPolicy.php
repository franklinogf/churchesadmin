<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Check;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class CheckPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $tenantUser, Check $check): Response
    {
        if ($check->isConfirmed()) {
            return Response::deny('You cannot edit a confirmed check.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $tenantUser, Check $check): Response
    {
        if ($check->isConfirmed()) {
            return Response::deny('You cannot delete a confirmed check.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(TenantUser $tenantUser, Check $check): Response
    {
        if ($check->isConfirmed()) {
            return Response::deny('You cannot permanently delete a confirmed check.');
        }

        return Response::allow();
    }
}
