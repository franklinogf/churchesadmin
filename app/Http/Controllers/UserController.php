<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\CreateUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\Enums\FlashMessageKey;
use App\Enums\TenantRole;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\PermissionResource;
use App\Http\Resources\User\RoleResource;
use App\Http\Resources\User\UserResource;
use App\Models\TenantUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(#[CurrentUser] TenantUser $user): Response
    {
        Gate::authorize('viewAny', TenantUser::class);

        $users = TenantUser::query()
            ->withoutRole(TenantRole::SUPER_ADMIN)
            ->whereNotIn('id', [$user->id])
            ->with('roles', 'permissions')->latest()->get();

        return Inertia::render('main/users/index', ['users' => UserResource::collection($users)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', TenantUser::class);

        $roles = Role::query()
            ->whereNotIn('name', [TenantRole::SUPER_ADMIN->value])
            ->with('permissions')
            ->get();

        $permissions = Permission::all();

        return Inertia::render('main/users/create', [
            'permissions' => PermissionResource::collection($permissions),
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, CreateUserAction $action): RedirectResponse
    {
        $action->handle(
            $request->getUserData(),
            $request->getRoleData(),
            $request->getAdditionalPermissionsData()
        );

        return to_route('users.index')
            ->with(
                FlashMessageKey::SUCCESS->value,
                __('flash.message.created', ['model' => __('User')])
            );
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(User $user): void
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TenantUser $user): Response
    {
        Gate::authorize('update', $user);

        $user->load('roles', 'permissions');
        $roles = Role::query()
            ->whereNotIn('name', [TenantRole::SUPER_ADMIN->value])
            ->with('permissions')
            ->get();

        $permissions = Permission::all();

        return Inertia::render('main/users/edit', [
            'user' => new UserResource($user),
            'permissions' => PermissionResource::collection($permissions),
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, TenantUser $user, UpdateUserAction $action): RedirectResponse
    {
        $action->handle(
            $user,
            $request->getUserData(),
            $request->getRoleData(),
            $request->getAdditionalPermissionsData()
        );

        return to_route('users.index')
            ->with(
                FlashMessageKey::SUCCESS->value,
                __('flash.message.updated', ['model' => __('User')])
            );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TenantUser $user, DeleteUserAction $action): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $action->handle($user);

        return to_route('users.index')
            ->with(
                FlashMessageKey::SUCCESS->value,
                __('flash.message.deleted', ['model' => __('User')])
            );
    }
}
