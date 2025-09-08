<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\ChurchWallet;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::WALLETS_MANAGE, TenantPermission::WALLETS_RESTORE);
    });

    it('can restore a wallet', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $wallet->delete(); // First soft delete it

        $this->put(route('wallets.restore', $wallet))
            ->assertRedirect(route('wallets.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('church_wallets', [
            'id' => $wallet->id,
            'deleted_at' => null,
        ]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot restore a wallet', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $wallet->delete(); // First soft delete it

        $this->put(route('wallets.restore', $wallet))
            ->assertStatus(403);
    });
});

it('cannot restore a wallet if not authenticated', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->delete(); // First soft delete it

    $this->put(route('wallets.restore', $wallet))
        ->assertRedirect(route('login'));
});
