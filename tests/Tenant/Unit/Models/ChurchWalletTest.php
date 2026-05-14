<?php

declare(strict_types=1);

use App\Models\CheckLayout;
use App\Models\ChurchWallet;
use Bavix\Wallet\Interfaces\Confirmable;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

test('to array', function (): void {
    $wallet = ChurchWallet::factory()->create()->fresh();

    expect(array_keys($wallet->toArray()))->toBe([
        'id',
        'name',
        'slug',
        'description',
        'bank_name',
        'bank_account_number',
        'bank_routing_number',
        'check_layout_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $wallet = ChurchWallet::factory()->create()->fresh();

    expect($wallet->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($wallet->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('can belong to a check layout', function (): void {
    $checkLayout = CheckLayout::factory()->create();
    $wallet = ChurchWallet::factory()->create([
        'check_layout_id' => $checkLayout->id,
    ])->fresh();

    expect($wallet->checkLayout)->toBeInstanceOf(CheckLayout::class);
    expect($wallet->checkLayout->id)->toBe($checkLayout->id);
});

it('can have no check layout', function (): void {
    $wallet = ChurchWallet::factory()->create([
        'check_layout_id' => null,
    ])->fresh();

    expect($wallet->check_layout_id)->toBeNull();
    expect($wallet->checkLayout)->toBeNull();
});

it('implements wallet interfaces', function (): void {
    $wallet = ChurchWallet::factory()->create();

    expect($wallet)->toBeInstanceOf(Confirmable::class);
    expect($wallet)->toBeInstanceOf(Wallet::class);
    expect($wallet)->toBeInstanceOf(WalletFloat::class);
});

it('uses soft deletes', function (): void {
    $wallet = ChurchWallet::factory()->create();

    $wallet->delete();

    expect($wallet->trashed())->toBeTrue();
    expect($wallet->deleted_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('can be restored after soft delete', function (): void {
    $wallet = ChurchWallet::factory()->create();

    $wallet->delete();

    expect($wallet->trashed())->toBeTrue();

    $wallet->restore();
    expect($wallet->trashed())->toBeFalse();
    expect($wallet->deleted_at)->toBeNull();
});

it('has initial transaction relationship', function (): void {
    $wallet = ChurchWallet::factory()->create();

    // The relationship exists even if no initial transaction is created yet
    expect($wallet->initialTransaction())->toBeInstanceOf(MorphOne::class);
});
