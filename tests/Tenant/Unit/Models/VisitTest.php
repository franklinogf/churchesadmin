<?php

declare(strict_types=1);

use App\Models\Address;
use App\Models\Email;
use App\Models\FollowUp;
use App\Models\Visit;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $visit = Visit::factory()->create()->fresh();

    expect(array_keys($visit->toArray()))->toBe([
        'id',
        'name',
        'last_name',
        'email',
        'phone',
        'first_visit_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $visit = Visit::factory()->create([
        'name' => 'john',
        'last_name' => 'doe',
        'first_visit_date' => '2023-01-01',
    ])->fresh();

    expect($visit->name)->toBe('John'); // AsUcWords cast
    expect($visit->last_name)->toBe('Doe'); // AsUcWords cast
    expect($visit->first_visit_date)->toBeInstanceOf(CarbonImmutable::class);
    expect($visit->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($visit->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('has an address', function (): void {
    $visit = Visit::factory()
        ->has(Address::factory())
        ->create()->fresh();

    expect($visit->address)->toBeInstanceOf(Address::class);
    expect($visit->address->owner_id)->toBe($visit->id);
    expect($visit->address->owner_type)->toBe($visit->getMorphClass());
});

it('can have follow ups', function (): void {
    $visit = Visit::factory()
        ->has(FollowUp::factory()->count(3), 'followUps')
        ->create();

    expect($visit->followUps)->toHaveCount(3);
    expect($visit->followUps->first())->toBeInstanceOf(FollowUp::class);
});

it('can have a last follow up', function (): void {
    $visit = Visit::factory()
        ->has(FollowUp::factory()->count(3), 'followUps')
        ->create();

    expect($visit->lastFollowUp)->toBeInstanceOf(FollowUp::class);
    expect($visit->lastFollowUp->visit_id)->toBe($visit->id);
});

it('uses soft deletes', function (): void {
    $visit = Visit::factory()->create();

    $visit->delete();

    expect($visit->trashed())->toBeTrue();
    expect($visit->deleted_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('can be restored after soft delete', function (): void {
    $visit = Visit::factory()->create();

    $visit->delete();
    expect($visit->trashed())->toBeTrue();

    $visit->restore();
    expect($visit->trashed())->toBeFalse();
    expect($visit->deleted_at)->toBeNull();
});

it('can have null first visit date', function (): void {
    $visit = Visit::factory()->create([
        'first_visit_date' => null,
    ]);

    expect($visit->first_visit_date)->toBeNull();
});

it('can have emails', function (): void {
    $visit = Visit::factory()
        ->has(Email::factory()->count(3), 'emails')
        ->create();

    expect($visit->emails)->toHaveCount(3);
    expect($visit->emails[0])->toBeInstanceOf(Email::class);
    expect($visit->emails[1])->toBeInstanceOf(Email::class);
    expect($visit->emails[2])->toBeInstanceOf(Email::class);
});
