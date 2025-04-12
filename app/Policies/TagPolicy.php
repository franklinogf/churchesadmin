<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TagType;
use App\Enums\TenantPermission;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class TagPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, bool $is_regular, ?TagType $tagType): Response
    {
        if ($is_regular && $user->cannot(TenantPermission::CREATE_REGULAR_TAG)) {
            return Response::deny(__('permission.create', ['label' => __('Regular tags')]));
        }

        return match ($tagType) {
            TagType::CATEGORY => $user->can(TenantPermission::CREATE_CATEGORIES) ? Response::allow() : Response::deny(__('permission.create', ['label' => $tagType->label()])),
            TagType::SKILL => $user->can(TenantPermission::CREATE_SKILLS) ? Response::allow() : Response::deny(__('permission.create', ['label' => $tagType->label()])),
            default => Response::deny(__('permission.create', ['label' => __('Tags')]))
        };
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tag $tag): Response
    {
        if ($tag->is_regular && $user->cannot(TenantPermission::UPDATE_REGULAR_TAG)) {
            return Response::deny(__('permission.update', ['label' => __('Regular tags')]));
        }
        $tagType = $tag->type ? TagType::tryFrom($tag->type) : null;

        return match ($tagType) {
            TagType::CATEGORY => $user->can(TenantPermission::UPDATE_CATEGORIES) ? Response::allow() : Response::deny(__('permission.update', ['label' => $tagType->label()])),
            TagType::SKILL => $user->can(TenantPermission::UPDATE_SKILLS) ? Response::allow() : Response::deny(__('permission.update', ['label' => $tagType->label()])),
            default => Response::deny(__('permission.update', ['label' => __('Tags')]))
        };
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tag $tag): Response
    {
        if ($tag->is_regular && $user->cannot(TenantPermission::DELETE_REGULAR_TAG)) {

            return Response::deny(__('permission.delete', ['label' => __('Regular tags')]));
        }

        $tagType = $tag->type ? TagType::tryFrom($tag->type) : null;

        return match ($tagType) {
            TagType::CATEGORY => $user->can(TenantPermission::DELETE_CATEGORIES) ? Response::allow() : Response::deny(__('permission.delete', ['label' => $tagType->label()])),
            TagType::SKILL => $user->can(TenantPermission::DELETE_SKILLS) ? Response::allow() : Response::deny(__('permission.delete', ['label' => $tagType->label()])),
            default => Response::deny(__('permission.delete', ['label' => __('Tags')]))
        };
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
