<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class CalendarEventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CALENDAR_EVENTS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Calendar Events')]));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CALENDAR_EVENTS_CREATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Calendar Events')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CALENDAR_EVENTS_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Calendar Events')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CALENDAR_EVENTS_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Calendar Events')]));
    }

    /**
     * Determine whether the user can export events.
     */
    public function export(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CALENDAR_EVENTS_EXPORT)) {
            return Response::allow();
        }

        return Response::deny(__('permission.export', ['label' => __('Calendar Events')]));
    }

    /**
     * Determine whether the user can email events.
     */
    public function email(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::CALENDAR_EVENTS_EMAIL)) {
            return Response::allow();
        }

        return Response::deny(__('permission.email', ['label' => __('Calendar Events')]));
    }
}
