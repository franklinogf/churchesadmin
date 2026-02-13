<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use App\Models\Address;
use App\Models\Email;
use App\Models\Missionary;
use App\Models\Offering;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $missionary = Missionary::factory()->create()->fresh();

    expect(array_keys($missionary->toArray()))->toBe([
        'id',
        'name',
        'last_name',
        'email',
        'phone',
        'gender',
        'church',
        'offering',
        'offering_frequency',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $missionary = Missionary::factory()->create([
        'offering_frequency' => OfferingFrequency::MONTHLY,
        'gender' => Gender::MALE,
    ])->fresh();

    expect($missionary->gender)->toBeInstanceOf(Gender::class);
    expect($missionary->offering_frequency)->toBeInstanceOf(OfferingFrequency::class);
    expect($missionary->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($missionary->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('has an address', function (): void {
    $missionary = Missionary::factory()
        ->has(Address::factory())->create();

    expect($missionary->address)->toBeInstanceOf(Address::class)
        ->and($missionary->address->owner_id)->toBe($missionary->id)
        ->and($missionary->address->owner_type)->toBe($missionary->getMorphClass());
});

it('has offerings', function (): void {
    $missionary = Missionary::factory()
        ->has(Offering::factory()->count(2))->create();

    expect($missionary->offerings)->toHaveCount(2);
    expect($missionary->offerings->first())->toBeInstanceOf(Offering::class);
});

it('uses soft deletes', function (): void {
    $missionary = Missionary::factory()->create();

    $missionary->delete();

    expect($missionary->trashed())->toBeTrue();
    expect($missionary->deleted_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('can be restored after soft delete', function (): void {
    $missionary = Missionary::factory()->create();

    $missionary->delete();
    expect($missionary->trashed())->toBeTrue();

    $missionary->restore();
    expect($missionary->trashed())->toBeFalse();
    expect($missionary->deleted_at)->toBeNull();
});

it('can have optional fields', function (): void {
    $missionary = Missionary::factory()->create([
        'email' => null,
        'phone' => null,
        'offering' => null,
    ]);

    expect($missionary->email)->toBeNull();
    expect($missionary->phone)->toBeNull();
    expect($missionary->offering)->toBeNull();
});

it('can have church name', function (): void {
    $missionary = Missionary::factory()->create([
        'church' => 'Test Church Name',
    ]);

    expect($missionary->church)->toBe('Test Church Name');
});

it('can have emails', function (): void {
    $missionary = Missionary::factory()
        ->has(Email::factory()->count(3), 'emails')
        ->create();

    expect($missionary->emails)->toHaveCount(3);
    expect($missionary->emails[0])->toBeInstanceOf(Email::class);
    expect($missionary->emails[1])->toBeInstanceOf(Email::class);
    expect($missionary->emails[2])->toBeInstanceOf(Email::class);
});
