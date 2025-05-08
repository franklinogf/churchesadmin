<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TenantRole;
use App\Models\TenantUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class LoginLinkController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'string', Rule::enum(TenantRole::class)],
        ]);

        /**
         * @var TenantRole $role
         */
        $role = TenantRole::tryFrom($request->string('role')->toString());

        $user = TenantUser::role($role)->firstOr(function () use ($role): TenantUser {
            $name = match ($role) {
                TenantRole::SUPER_ADMIN => 'Super Admin',
                TenantRole::ADMIN => 'Admin',
                TenantRole::SECRETARY => 'Secretary',
                TenantRole::NO_ROLE => 'No Role',
            };
            $email = match ($role) {
                TenantRole::SUPER_ADMIN => 'superadmin@example.com',
                TenantRole::ADMIN => 'admin@example.com',
                TenantRole::SECRETARY => 'secretary@example.com',
                TenantRole::NO_ROLE => 'norole@example.com',
            };
            $user = TenantUser::create([
                'name' => $name,
                'email' => $email,
                'password' => 'Password123',
            ]);
            $user->assignRole($role);

            return $user;
        });

        Auth::guard('tenant')->login($user);

        return to_route('dashboard');
    }
}
