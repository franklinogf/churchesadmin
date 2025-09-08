<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\ChurchWallet;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('wallets.index'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::WALLETS_MANAGE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('wallets.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('wallets/index')
                ->has('wallets')
            );
    });

    it('displays wallets in the list', function (): void {
        $wallets = ChurchWallet::factory()->count(3)->create();

        get(route('wallets.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('wallets/index')
                ->has('wallets', 3)
                ->has('wallets.0', fn (Assert $page): Assert => $page
                    ->where('id', $wallets[0]->id)
                    ->etc()
                )
            );
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the wallets index', function (): void {
        get(route('wallets.index'))
            ->assertStatus(403);
    });
});
