<?php

declare(strict_types=1);

use App\Actions\Offering\UpdateOfferingAction;
use App\Enums\PaymentMethod;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Models\Member;
use App\Models\Offering;
use App\Models\OfferingType;

it('can update offering with new data', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('200.00');

    $member = Member::factory()->create();
    $newMember = Member::factory()->create();
    $offeringType = OfferingType::factory()->create();
    $newOfferingType = OfferingType::factory()->create();

    Offering::factory()->create([
        'donor_id' => $member->id,
        'offering_type_id' => $offeringType->id,
        'offering_type_type' => OfferingType::class,
        'date' => '2024-01-01',
        'payment_method' => 'cash',
        'note' => 'Original note',
    ]);
    $offering = Offering::latest()->first();

    $updateData = [
        'date' => '2024-01-15',
        'donor_id' => $newMember->id,
        'amount' => '75.00',
        'payment_method' => PaymentMethod::CHECK,
        'note' => 'Updated offering note',
        'offering_type' => [
            'id' => $newOfferingType->id,
            'model' => OfferingType::class,
        ],
    ];

    $action = app(UpdateOfferingAction::class);
    $updatedOffering = $action->handle($offering, $updateData);

    expect($updatedOffering->date->format('Y-m-d'))->toBe('2024-01-15')
        ->and($updatedOffering->donor_id)->toBe($newMember->id)
        ->and($updatedOffering->payment_method)->toBe(PaymentMethod::CHECK)
        ->and($updatedOffering->note)->toBe('Updated offering note')
        ->and($updatedOffering->offering_type_id)->toBe($newOfferingType->id)
        ->and($updatedOffering->transaction->amountFloat)->toBe('75.00');
});

it('can update offering with partial data', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('200.00');

    $member = Member::factory()->create();
    $offeringType = OfferingType::factory()->create();

    Offering::factory()->create([
        'donor_id' => $member->id,
        'offering_type_id' => $offeringType->id,
        'offering_type_type' => OfferingType::class,
        'date' => '2024-01-01',
        'payment_method' => PaymentMethod::CHECK,
        'note' => 'Original note',
    ]);
    $offering = Offering::latest()->first();

    $updateData = [
        'note' => 'Only note updated',
        'payment_method' => PaymentMethod::CASH,
    ];

    $action = app(UpdateOfferingAction::class);
    $updatedOffering = $action->handle($offering, $updateData);

    expect($updatedOffering->note)->toBe('Only note updated')
        ->and($updatedOffering->payment_method)->toBe(PaymentMethod::CASH)
        ->and($updatedOffering->donor_id)->toBe($member->id)
        ->and($updatedOffering->date->format('Y-m-d'))->toBe('2024-01-01');
});

it('can clear donor_id and note with null values', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $wallet->depositFloat('200.00');

    $member = Member::factory()->create();
    $offeringType = OfferingType::factory()->create();

    Offering::factory()->create([
        'donor_id' => $member->id,
        'offering_type_id' => $offeringType->id,
        'offering_type_type' => OfferingType::class,
        'note' => 'Original note',
    ]);
    $offering = Offering::latest()->first();

    $updateData = [
        'donor_id' => null,
        'note' => null,
    ];

    $action = app(UpdateOfferingAction::class);
    $updatedOffering = $action->handle($offering, $updateData);

    expect($updatedOffering->donor_id)->toBeNull()
        ->and($updatedOffering->note)->toBeNull();
});

it('throws exception when wallet not found', function (): void {
    $offering = Offering::factory()->create();

    $updateData = [
        'wallet_id' => 'non-existent-id',
        'amount' => '50.00',
    ];

    $action = app(UpdateOfferingAction::class);

    expect(fn () => $action->handle($offering, $updateData))
        ->toThrow(WalletException::class);
});
