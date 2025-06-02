<?php

declare(strict_types=1);

use App\Actions\Offering\CreateOfferingAction;
use App\Enums\PaymentMethod;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Models\Member;
use App\Models\Offering;
use App\Models\OfferingType;

it('can create an offering with donor', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $member = Member::factory()->create();
    $offeringType = OfferingType::factory()->create();

    $offeringData = [
        'wallet_id' => $wallet->id,
        'date' => '2024-01-01',
        'donor_id' => $member->id,
        'amount' => '100.00',
        'payment_method' => PaymentMethod::CASH,
        'note' => 'Sunday offering',
        'offering_type' => [
            'id' => $offeringType->id,
            'model' => OfferingType::class,
        ],
    ];

    $action = app(CreateOfferingAction::class);
    $offering = $action->handle($offeringData);

    expect($offering)->toBeInstanceOf(Offering::class)
        ->and($offering->date->format('Y-m-d'))->toBe('2024-01-01')
        ->and($offering->donor_id)->toBe($member->id)
        ->and($offering->payment_method)->toBe(PaymentMethod::CASH)
        ->and($offering->note)->toBe('Sunday offering')
        ->and($offering->offering_type_id)->toBe($offeringType->id)
        ->and($offering->offering_type_type)->toBe(OfferingType::class)
        ->and($offering->transaction)->not->toBeNull()
        ->and($offering->transaction->amountFloat)->toBe('100.00');

    // Check wallet balance was increased
    expect($wallet->fresh()->balanceFloat)->toBe('100.00');
});

it('can create an offering without donor', function (): void {
    $wallet = ChurchWallet::factory()->create();
    $offeringType = OfferingType::factory()->create();

    $offeringData = [
        'wallet_id' => $wallet->id,
        'date' => '2024-01-01',
        'amount' => '50.00',
        'payment_method' => PaymentMethod::CHECK,
        'offering_type' => [
            'id' => $offeringType->id,
            'model' => OfferingType::class,
        ],
    ];

    $action = app(CreateOfferingAction::class);
    $offering = $action->handle($offeringData);

    expect($offering)->toBeInstanceOf(Offering::class)
        ->and($offering->donor_id)->toBeNull()
        ->and($offering->note)->toBeNull()
        ->and($offering->payment_method)->toBe(PaymentMethod::CHECK)
        ->and($offering->transaction->amountFloat)->toBe('50.00');
});

it('throws exception when wallet not found', function (): void {
    $offeringType = OfferingType::factory()->create();

    $offeringData = [
        'wallet_id' => 'non-existent-id',
        'date' => '2024-01-01',
        'amount' => '50.00',
        'payment_method' => PaymentMethod::CASH,
        'offering_type' => [
            'id' => $offeringType->id,
            'model' => OfferingType::class,
        ],
    ];

    $action = app(CreateOfferingAction::class);

    $action->handle($offeringData);
})->throws(WalletException::class);
