<?php

namespace App\Policies;

use App\Models\TenantUser;
use App\Models\Visit;
use Illuminate\Auth\Access\Response;

class VisitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(TenantUser $tenantUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(TenantUser $tenantUser, Visit $visit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(TenantUser $tenantUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $tenantUser, Visit $visit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $tenantUser, Visit $visit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(TenantUser $tenantUser, Visit $visit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(TenantUser $tenantUser, Visit $visit): bool
    {
        return false;
    }
}
