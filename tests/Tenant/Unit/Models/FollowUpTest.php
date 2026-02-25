<?php

declare(strict_types=1);

use App\Enums\FollowUpType;
use App\Models\FollowUp;
use App\Models\Member;
use App\Models\Visit;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $followUp = FollowUp::factory()->create()->fresh();

    expect(array_keys($followUp->toArray()))->toBe([
        'id',
        'visit_id',
        'member_id',
        'type',
        'follow_up_at',
        'notes',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $followUp = FollowUp::factory()->create()->fresh();

    expect($followUp->type)->toBeInstanceOf(FollowUpType::class);
    expect($followUp->follow_up_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($followUp->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($followUp->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to a visit', function (): void {
    $followUp = FollowUp::factory()->create()->fresh();

    expect($followUp->visit)->toBeInstanceOf(Visit::class);
    expect($followUp->visit->id)->toBe($followUp->visit_id);
});

it('belongs to a member', function (): void {
    $followUp = FollowUp::factory()->create()->fresh();

    expect($followUp->member)->toBeInstanceOf(Member::class);
    expect($followUp->member->id)->toBe($followUp->member_id);
});

it('uses soft deletes', function (): void {
    $followUp = FollowUp::factory()->create();

    $followUp->delete();

    expect($followUp->trashed())->toBeTrue();
    expect($followUp->deleted_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('can be restored after soft delete', function (): void {
    $followUp = FollowUp::factory()->create();

    $followUp->delete();

    expect($followUp->trashed())->toBeTrue();

    $followUp->restore();
    expect($followUp->trashed())->toBeFalse();
    expect($followUp->deleted_at)->toBeNull();
});
