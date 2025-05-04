<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Enums\TenantRole;
use App\Models\TenantUser;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\from;
use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('users.create'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_USERS, TenantPermission::CREATE_USERS);
        $this->travel(1)->days();
    });

    it('can be rendered if authenticated', function (): void {

        get(route('users.create'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('users/create')
                ->has('permissions')
                ->has('roles')
            );

    });

    it('can be stored', function (): void {

        from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'John',
                'email' => 'john.doe@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'roles' => [TenantRole::SECRETARY->value],
                'additional_permissions' => [TenantPermission::MANAGE_USERS->value],
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('users.index'));

        $user = TenantUser::latest()->first();

        expect($user)->not->toBeNull()
            ->and($user->name)->toBe('John')
            ->and($user->email)->toBe('john.doe@example.com')
            ->and($user->hasRole(TenantRole::SECRETARY))->toBeTrue()
            ->and($user->hasDirectPermission(TenantPermission::MANAGE_USERS))->toBeTrue();
    });

    it('can be stored without additional permissions', function (): void {

        from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'John',
                'email' => 'john.doe@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'roles' => [TenantRole::SECRETARY->value],
                'additional_permissions' => [],
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('users.index'));

        $user = TenantUser::latest()->first();

        expect($user)->not->toBeNull()
            ->and($user->name)->toBe('John')
            ->and($user->email)->toBe('john.doe@example.com')
            ->and($user->hasRole(TenantRole::SECRETARY))->toBeTrue()
            ->and($user->permissions()->count())->toBe(0);

    });

});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::MANAGE_USERS);
    });

    it('cannot be rendered if authenticated', function (): void {
        get(route('users.create'))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);
    });

    it('cannot be stored', function (): void {
        from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'John',
                'email' => 'john.doe@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'roles' => [TenantRole::SECRETARY->value],
                'additional_permissions' => [TenantPermission::MANAGE_USERS->value],
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas(FlashMessageKey::ERROR->value);
    });
});
