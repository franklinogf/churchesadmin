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
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(#[CurrentUser] User $user)
    {
        $users = User::query()
            ->withoutRole(TenantRole::SUPER_ADMIN)
            ->whereNotIn('id', [$user->id])
            ->with('roles', 'permissions')->latest()->get();

        return Inertia::render('users/index', ['users' => UserResource::collection($users)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::query()
            ->whereNotIn('name', [TenantRole::SUPER_ADMIN->value])
            ->with('permissions')
            ->get();

        $permissions = Permission::all();

        return Inertia::render('users/create', [
            'permissions' => PermissionResource::collection($permissions),
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, CreateUserAction $action)
    {
        $action->handle(
            $request->getUserData(),
            $request->getRoleData(),
            $request->getAdditionalPermissionsData()
        );

        return to_route('users.index')
            ->with(FlashMessageKey::SUCCESS->value, __('User created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('roles', 'permissions');
        $roles = Role::query()
            ->whereNotIn('name', [TenantRole::SUPER_ADMIN->value])
            ->with('permissions')
            ->get();

        $permissions = Permission::all();

        return Inertia::render('users/edit', [
            'user' => new UserResource($user),
            'permissions' => PermissionResource::collection($permissions),
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action)
    {
        $action->handle(
            $user,
            $request->getUserData(),
            $request->getRoleData(),
            $request->getAdditionalPermissionsData()
        );

        return to_route('users.index')
            ->with(FlashMessageKey::SUCCESS->value, __('User updated successfully'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteUserAction $action)
    {
        $action->handle($user);

        return to_route('users.index')
            ->with(FlashMessageKey::SUCCESS->value, __('User deleted successfully'));
    }
}
