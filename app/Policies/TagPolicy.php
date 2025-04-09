<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TagType;
use App\Enums\TenantPermissionName;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): Response
    {

        return Response::deny();
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
    public function create(User $user, TagType $tagType): Response
    {
        if ($tagType === TagType::CATEGORY) {
            if ($user->can(TenantPermissionName::CREATE_CATEGORIES)) {
                return Response::allow();
            }

            return Response::deny(__('permission.create', ['label' => $tagType->label()]));
        }

        if ($tagType === TagType::SKILL) {
            if ($user->can(TenantPermissionName::CREATE_SKILLS)) {
                return Response::allow();
            }

            return Response::deny(__('permission.create', ['label' => $tagType->label()]));
        }

        return Response::deny(__('permission.create', ['label' => __('Tags')]));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tag $tag): Response
    {
        if ($user->can(TenantPermissionName::UPDATE_CATEGORIES) && $tag->type === TagType::CATEGORY->value) {
            return Response::allow();
        }

        if ($user->can(TenantPermissionName::UPDATE_SKILLS) && $tag->type === TagType::SKILL->value) {
            return Response::allow();
        }

        if ($user->can(TenantPermissionName::UPDATE_REGULAR_TAG) && $tag->is_regular) {
            return Response::allow();
        }

        if ($tag->type !== null) {
            $tagType = TagType::tryFrom($tag->type);

            if ($tagType) {
                return Response::deny(__('permission.update', ['label' => $tagType->label()]));
            }
        }

        return Response::deny(__('permission.update', ['label' => __('Tags')]));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tag $tag): Response
    {
        if ($tag->is_regular && $user->cannot(TenantPermissionName::DELETE_REGULAR_TAG)) {

            return Response::deny(__('permission.delete', ['label' => __('Regular tags')]));
        }

        if ($user->can(TenantPermissionName::DELETE_CATEGORIES) && $tag->type === TagType::CATEGORY->value) {
            return Response::allow();
        }

        if ($user->can(TenantPermissionName::DELETE_SKILLS) && $tag->type === TagType::SKILL->value) {
            return Response::allow();
        }

        if ($tag->type !== null) {

            $tagType = TagType::tryFrom($tag->type);
            if ($tagType) {
                return Response::deny(__('permission.delete', ['label' => $tagType->label()]));
            }
        }

        return Response::deny(__('permission.delete', ['label' => __('Tags')]));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(): bool
    {
        return false;
    }
}
