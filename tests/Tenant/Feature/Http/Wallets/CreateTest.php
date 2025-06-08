<?php

declare(strict_types=1);

use App\Enums\TenantPermission;

use function Pest\Laravel\assertDatabaseHas;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::WALLETS_MANAGE, TenantPermission::WALLETS_CREATE);
    });

    it('can store a wallet', function (): void {
        $walletData = [
            'name' => 'General Fund',
            'description' => 'Main church wallet',
            'bank_name' => 'Bank of America',
            'bank_routing_number' => '123456789',
            'bank_account_number' => '987654321',
            'balance' => '1000.00',
        ];

        $this->post(route('wallets.store'), $walletData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('wallets.index'))
            ->assertSessionHas('success');

        assertDatabaseHas('church_wallets', [
            'name' => 'General Fund',
            'description' => 'Main church wallet',
            'bank_name' => 'Bank of America',
            'bank_routing_number' => '123456789',
            'bank_account_number' => '987654321',
        ]);
    });

    it('validates required fields', function (): void {
        $this->post(route('wallets.store'), [])
            ->assertSessionHasErrors(['name', 'bank_name', 'bank_routing_number', 'bank_account_number']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot store a wallet', function (): void {
        $walletData = [
            'name' => 'General Fund',
            'bank_name' => 'Bank of America',
            'bank_routing_number' => '123456789',
            'bank_account_number' => '987654321',
        ];

        $this->post(route('wallets.store'), $walletData)
            ->assertStatus(403);
    });
});

it('cannot store a wallet if not authenticated', function (): void {
    $walletData = [
        'name' => 'General Fund',
        'bank_name' => 'Bank of America',
        'bank_routing_number' => '123456789',
        'bank_account_number' => '987654321',
    ];

    $this->post(route('wallets.store'), $walletData)
        ->assertRedirect(route('login'));
});
