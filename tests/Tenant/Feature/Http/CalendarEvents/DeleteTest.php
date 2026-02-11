<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\CalendarEvent;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;

it('cannot delete a calendar event if not authenticated', function (): void {
    $event = CalendarEvent::factory()->create();

    delete(route('calendar-events.destroy', $event))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CALENDAR_EVENTS_MANAGE, TenantPermission::CALENDAR_EVENTS_DELETE);
    });

    it('can delete a calendar event', function (): void {
        $event = CalendarEvent::factory()->create();

        delete(route('calendar-events.destroy', $event))
            ->assertRedirect(route('calendar-events.index'))
            ->assertSessionHas('success');

        assertSoftDeleted('calendar_events', ['id' => $event->id]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot delete a calendar event', function (): void {
        $event = CalendarEvent::factory()->create();

        delete(route('calendar-events.destroy', $event))
            ->assertStatus(403);

        assertDatabaseHas('calendar_events', ['id' => $event->id]);
    });
});
