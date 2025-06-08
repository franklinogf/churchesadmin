<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\ChurchWallet;

use function Pest\Laravel\assertDatabaseHas;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::WALLETS_MANAGE, TenantPermission::WALLETS_UPDATE);
    });

    it('can update a wallet', function (): void {
        $wallet = ChurchWallet::factory()->create([
            'name' => 'Original Name',
            'description' => 'Original Description',
        ]);

        $updateData = [
            'name' => 'Updated Fund',
            'description' => 'Updated church wallet',
            'bank_name' => 'Updated Bank',
            'bank_routing_number' => '987654321',
            'bank_account_number' => '123456789',
        ];

        $this->put(route('wallets.update', $wallet), $updateData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('wallets.index'))
            ->assertSessionHas('success');

        assertDatabaseHas('church_wallets', [
            'id' => $wallet->id,
            'name' => 'Updated Fund',
            'description' => 'Updated church wallet',
            'bank_name' => 'Updated Bank',
        ]);
    });

    it('validates required fields on update', function (): void {
        $wallet = ChurchWallet::factory()->create();

        $this->put(route('wallets.update', $wallet), [
            'name' => '',
            'bank_name' => '',
        ])
            ->assertSessionHasErrors(['name', 'bank_name', 'bank_routing_number', 'bank_account_number']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot update a wallet', function (): void {
        $wallet = ChurchWallet::factory()->create();

        $updateData = [
            'name' => 'Updated Fund',
            'bank_name' => 'Updated Bank',
            'bank_routing_number' => '987654321',
            'bank_account_number' => '123456789',
        ];

        $this->put(route('wallets.update', $wallet), $updateData)
            ->assertStatus(403);
    });
});

it('cannot update a wallet if not authenticated', function (): void {
    $wallet = ChurchWallet::factory()->create();

    $updateData = [
        'name' => 'Updated Fund',
        'bank_name' => 'Updated Bank',
        'bank_routing_number' => '987654321',
        'bank_account_number' => '123456789',
    ];

    $this->put(route('wallets.update', $wallet), $updateData)
        ->assertRedirect(route('login'));
});
