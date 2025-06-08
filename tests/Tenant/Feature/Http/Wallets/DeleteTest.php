<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\ChurchWallet;

use function Pest\Laravel\assertSoftDeleted;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::WALLETS_MANAGE, TenantPermission::WALLETS_DELETE);
    });

    it('can delete a wallet', function (): void {
        $wallet = ChurchWallet::factory()->create();

        $this->delete(route('wallets.destroy', $wallet))
            ->assertRedirect(route('wallets.index'))
            ->assertSessionHas('success');

        assertSoftDeleted('church_wallets', ['id' => $wallet->id]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot delete a wallet', function (): void {
        $wallet = ChurchWallet::factory()->create();

        $this->delete(route('wallets.destroy', $wallet))
            ->assertStatus(403);

        $this->assertDatabaseHas('church_wallets', ['id' => $wallet->id]);
    });
});

it('cannot delete a wallet if not authenticated', function (): void {
    $wallet = ChurchWallet::factory()->create();

    $this->delete(route('wallets.destroy', $wallet))
        ->assertRedirect(route('login'));
});
