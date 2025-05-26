<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ModelMorphName;
use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class EmailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(TenantUser $user): Response
    {
        if ($user->cannot(TenantPermission::EMAILS_MANAGE)) {
            return Response::deny(__('permission.view_any', ['label' => __('Emails')]));
        }

        return Response::allow();
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
    public function create(TenantUser $user, ?ModelMorphName $recipientType = null): Response
    {
        if ($user->cannot(TenantPermission::EMAILS_CREATE)) {
            return Response::deny();
        }

        return match (true) {
            $user->cannot(TenantPermission::EMAILS_SEND_TO_MEMBERS) && $recipientType === ModelMorphName::MEMBER => Response::deny(__('permission.create', ['label' => __('Emails')])),
            $user->cannot(TenantPermission::EMAILS_SEND_TO_MISSIONARIES) && $recipientType === ModelMorphName::MISSIONARY => Response::deny(__('permission.create', ['label' => __('Emails')])),
            default => Response::allow()
        };

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::EMAILS_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Emails')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::EMAILS_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Emails')]));
    }
}
