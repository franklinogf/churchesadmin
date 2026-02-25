<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class ExpenseTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::EXPENSE_TYPES_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Expense Types')]));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::EXPENSE_TYPES_CREATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Expense Types')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::EXPENSE_TYPES_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Expense Types')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::EXPENSE_TYPES_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Expense Types')]));
    }
}
