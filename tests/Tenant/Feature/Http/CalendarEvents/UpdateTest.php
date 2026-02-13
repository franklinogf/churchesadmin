<?php

declare(strict_types=1);

use App\Enums\CalendarEventColorEnum;
use App\Enums\TenantPermission;
use App\Models\CalendarEvent;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\from;
use function Pest\Laravel\put;

it('cannot update a calendar event if not authenticated', function (): void {
    $event = CalendarEvent::factory()->create();

    $eventData = [
        'title' => 'Updated Service',
        'color' => $event->color->value,
        'start_at' => $event->start_at->toISOString(),
        'end_at' => $event->end_at->toISOString(),
    ];

    put(route('calendar-events.update', $event), $eventData)
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CALENDAR_EVENTS_MANAGE, TenantPermission::CALENDAR_EVENTS_UPDATE);
    });

    it('can update a calendar event', function (): void {
        $event = CalendarEvent::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'location' => 'Original Location',
            'color' => CalendarEventColorEnum::GREEN->value,
        ]);

        $eventData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'location' => 'Updated Location',
            'color' => CalendarEventColorEnum::RED->value,
            'start_at' => $event->start_at->toISOString(),
            'end_at' => $event->end_at->toISOString(),
        ];

        from(route('calendar-events.index'))
            ->put(route('calendar-events.update', $event), $eventData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('calendar-events.index'))
            ->assertSessionHas('success');

        assertDatabaseHas('calendar_events', [
            'id' => $event->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'location' => 'Updated Location',
            'color' => CalendarEventColorEnum::RED->value,
        ]);
    });

    it('can update calendar event times', function (): void {
        $event = CalendarEvent::factory()->create();

        $newStartAt = now()->addDays(2)->setTime(14, 0);
        $newEndAt = now()->addDays(2)->setTime(17, 0);

        $eventData = [
            'title' => $event->title,
            'color' => $event->color->value,
            'start_at' => $newStartAt->toISOString(),
            'end_at' => $newEndAt->toISOString(),
        ];

        from(route('calendar-events.index'))
            ->put(route('calendar-events.update', $event), $eventData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('calendar-events.index'));

        $event->refresh();

        expect($event->start_at->format('Y-m-d H:i:s'))->toBe($newStartAt->format('Y-m-d H:i:s'))
            ->and($event->end_at->format('Y-m-d H:i:s'))->toBe($newEndAt->format('Y-m-d H:i:s'));
    });

    it('validates required fields when updating', function (): void {
        $event = CalendarEvent::factory()->create();

        from(route('calendar-events.index'))
            ->put(route('calendar-events.update', $event), [])
            ->assertSessionHasErrors(['title', 'start_at', 'end_at']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot update a calendar event', function (): void {
        $event = CalendarEvent::factory()->create();

        $eventData = [
            'title' => 'Updated Title',
            'color' => $event->color->value,
            'start_at' => $event->start_at->toISOString(),
            'end_at' => $event->end_at->toISOString(),
        ];

        put(route('calendar-events.update', $event), $eventData)
            ->assertStatus(403);
    });
});
