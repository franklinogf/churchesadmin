<?php

declare(strict_types=1);

use App\Enums\CalendarEventColorEnum;
use App\Models\CalendarEvent;
use App\Models\TenantUser;
use Carbon\CarbonImmutable;

/**
 * @see CalendarEvent
 */
test('to array', function (): void {
    $event = CalendarEvent::factory()->create()->fresh();

    expect(array_keys($event->toArray()))->toBe([
        'id',
        'title',
        'description',
        'location',
        'color',
        'start_at',
        'end_at',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $event = CalendarEvent::factory()->create([
        'color' => CalendarEventColorEnum::GREEN->value,
        'start_at' => '2024-05-01 09:00:00',
        'end_at' => '2024-05-01 12:00:00',
    ])->fresh();

    expect($event->color)->toBe(CalendarEventColorEnum::GREEN);
    expect($event->start_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($event->end_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to a creator', function (): void {
    $user = TenantUser::factory()->create();

    $event = CalendarEvent::factory()->create([
        'created_by' => $user->id,
    ])->fresh();

    expect($event->creator)->toBeInstanceOf(TenantUser::class);
    expect($event->creator->id)->toBe($user->id);
});

it('supports soft deletes', function (): void {
    $event = CalendarEvent::factory()->create();

    $event->delete();

    $trashedEvent = CalendarEvent::withTrashed()->find($event->id);

    expect($trashedEvent)->not->toBeNull();
    expect($trashedEvent->trashed())->toBeTrue();
});
