<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\Church;
use App\Models\TenantUser;
use Illuminate\Auth\Access\Response;

final class FirstCommunionCertificatePolicy
{
    public function before(): ?Response
    {
        if (! $this->booksFeatureEnabled()) {
            return Response::deny(__('permission.view_any', ['label' => __('Books')]));
        }

        return null;
    }

    public function viewAny(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::BOOKS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Books')]));
    }

    public function create(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::BOOKS_CREATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.create', ['label' => __('Books')]));
    }

    public function update(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::BOOKS_UPDATE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.update', ['label' => __('Books')]));
    }

    public function delete(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::BOOKS_DELETE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.delete', ['label' => __('Books')]));
    }

    public function print(TenantUser $user): Response
    {
        if ($user->can(TenantPermission::BOOKS_MANAGE)) {
            return Response::allow();
        }

        return Response::deny(__('permission.view_any', ['label' => __('Books')]));
    }

    private function booksFeatureEnabled(): bool
    {
        $church = Church::current();

        return $church instanceof Church && $church->features->books;
    }
}
