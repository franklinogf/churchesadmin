<?php

declare(strict_types=1);

use App\Enums\PaymentMethod;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\Offering;
use App\Models\OfferingType;
use Bavix\Wallet\Models\Transaction;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\Relation;

test('to array', function (): void {
    $offering = Offering::factory()->create()->fresh();

    expect(array_keys($offering->toArray()))->toBe([
        'id',
        'transaction_id',
        'donor_id',
        'date',
        'payment_method',
        'offering_type_type',
        'offering_type_id',
        'note',
        'current_year_id',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $offering = Offering::factory()->create()->fresh();

    expect($offering->date)->toBeInstanceOf(CarbonImmutable::class);
    expect($offering->payment_method)->toBeInstanceOf(PaymentMethod::class);
    expect($offering->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($offering->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to a transaction', function (): void {
    $offering = Offering::factory()->create()->fresh();

    expect($offering->transaction)->toBeInstanceOf(Transaction::class);
    expect($offering->transaction->id)->toBe($offering->transaction_id);
});

it('can have offering type as OfferingType', function (): void {
    $offering = Offering::factory()->for(OfferingType::factory(), 'offeringType')->create()->fresh();

    expect($offering->offeringType)->toBeInstanceOf(OfferingType::class);
    expect($offering->offering_type_type)->toBe(Relation::getMorphAlias(OfferingType::class));
});

it('can have offering type as Missionary', function (): void {
    $offering = Offering::factory()->for(Missionary::factory(), 'offeringType')->create()->fresh();

    expect($offering->offeringType)->toBeInstanceOf(Missionary::class);
    expect($offering->offering_type_type)->toBe(Relation::getMorphAlias(Missionary::class));
});

it('can belong to a donor', function (): void {
    $offering = Offering::factory()->create()->fresh();

    if ($offering->donor_id) {
        expect($offering->donor)->toBeInstanceOf(Member::class);
        expect($offering->donor->id)->toBe($offering->donor_id);
    } else {
        expect($offering->donor)->toBeNull();
    }
});

it('can have no associated donor', function (): void {
    $offering = Offering::factory()->create([
        'donor_id' => null,
    ])->fresh();

    expect($offering->donor_id)->toBeNull();
    expect($offering->donor)->toBeNull();
});
