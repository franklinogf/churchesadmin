<?php

declare(strict_types=1);

use App\Actions\Wallet\UpdateWalletAction;
use App\Enums\TransactionMetaType;
use App\Models\CheckLayout;
use App\Models\ChurchWallet;

it('can update wallet basic information', function (): void {
    $wallet = ChurchWallet::factory()->create([
        'name' => 'Original Name',
        'description' => 'Original Description',
        'bank_name' => 'Original Bank',
        'bank_routing_number' => '111111111',
        'bank_account_number' => '111111111',
    ]);

    $data = [
        'name' => 'Updated Name',
        'description' => 'Updated Description',
        'bank_name' => 'Updated Bank',
        'bank_routing_number' => '222222222',
        'bank_account_number' => '222222222',
    ];

    $action = app(UpdateWalletAction::class);
    $updatedWallet = $action->handle($wallet, $data);

    expect($updatedWallet->name)->toBe('Updated Name');
    expect($updatedWallet->description)->toBe('Updated Description');
    expect($updatedWallet->bank_name)->toBe('Updated Bank');
    expect($updatedWallet->bank_routing_number)->toBe('222222222');
    expect($updatedWallet->bank_account_number)->toBe('222222222');

    $this->assertDatabaseHas('church_wallets', [
        'id' => $wallet->id,
        'name' => 'Updated Name',
        'description' => 'Updated Description',
        'bank_name' => 'Updated Bank',
    ]);
});

it('can update wallet with partial data', function (): void {
    $wallet = ChurchWallet::factory()->create([
        'name' => 'Original Name',
        'description' => 'Original Description',
        'bank_name' => 'Original Bank',
    ]);

    $data = [
        'name' => 'Updated Name Only',
    ];

    $action = app(UpdateWalletAction::class);
    $updatedWallet = $action->handle($wallet, $data);

    expect($updatedWallet->name)->toBe('Updated Name Only');
    expect($updatedWallet->description)->toBe('Original Description');
    expect($updatedWallet->bank_name)->toBe('Original Bank');
});

it('can update wallet check layout', function (): void {
    $oldLayout = CheckLayout::factory()->create();
    $newLayout = CheckLayout::factory()->create();

    $wallet = ChurchWallet::factory()->create([
        'check_layout_id' => $oldLayout->id,
    ]);

    $data = [
        'check_layout_id' => $newLayout->id,
    ];

    $action = app(UpdateWalletAction::class);
    $updatedWallet = $action->handle($wallet, $data);

    expect($updatedWallet->check_layout_id)->toBe($newLayout->id);
});

it('can set description to null', function (): void {
    $wallet = ChurchWallet::factory()->create([
        'description' => 'Original Description',
    ]);

    $data = [
        'description' => null,
    ];

    $action = app(UpdateWalletAction::class);
    $updatedWallet = $action->handle($wallet, $data);

    expect($updatedWallet->description)->toBeNull();
});

it('can set check layout to null', function (): void {
    $layout = CheckLayout::factory()->create();
    $wallet = ChurchWallet::factory()->create([
        'check_layout_id' => $layout->id,
    ]);

    $data = [
        'check_layout_id' => null,
    ];

    $action = app(UpdateWalletAction::class);
    $updatedWallet = $action->handle($wallet, $data);

    expect($updatedWallet->check_layout_id)->toBeNull();
});

it('can update existing initial balance', function (): void {
    $wallet = ChurchWallet::factory()->create();

    // Create initial transaction
    $wallet->depositFloat('50.00', ['type' => TransactionMetaType::INITIAL->value], true);
    expect($wallet->balanceFloat)->toBe('50.00');

    $data = [
        'balance' => '75.25',
    ];

    $action = app(UpdateWalletAction::class);
    $updatedWallet = $action->handle($wallet, $data);

    expect($updatedWallet->balanceFloat)->toBe('75.25');

    $initialTransaction = $updatedWallet->initialTransaction;
    expect($initialTransaction)->not->toBeNull();
    expect($initialTransaction->amountFloat)->toBe('75.25');
});

it('can remove initial balance by setting to null', function (): void {
    $wallet = ChurchWallet::factory()->create();

    // Create initial transaction
    $wallet->depositFloat('50.00', ['type' => TransactionMetaType::INITIAL->value], true);
    expect($wallet->balanceFloat)->toBe('50.00');

    $data = [
        'balance' => null,
    ];

    $action = app(UpdateWalletAction::class);
    $updatedWallet = $action->handle($wallet, $data);

    expect($updatedWallet->balanceFloat)->toBe('0.00');
    expect($updatedWallet->initialTransaction)->toBeNull();
});
