<?php

declare(strict_types=1);

use App\Enums\TenantPermission;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\from;
use function Pest\Laravel\post;

it('cannot store a calendar event if not authenticated', function (): void {
    $eventData = [
        'title' => 'Sunday Service',
        'start_at' => now()->addDay()->setTime(9, 0)->format('Y-m-d H:i:s'),
        'end_at' => now()->addDay()->setTime(11, 0)->format('Y-m-d H:i:s'),
    ];

    post(route('calendar-events.store'), $eventData)
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CALENDAR_EVENTS_MANAGE, TenantPermission::CALENDAR_EVENTS_CREATE);
    });

    it('can store a calendar event', function (): void {
        $eventData = [
            'title' => 'Sunday Service',
            'description' => 'Weekly Sunday morning service',
            'location' => 'Main Sanctuary',
            'start_at' => now()->addDay()->setTime(9, 0)->toISOString(),
            'end_at' => now()->addDay()->setTime(11, 0)->toISOString(),
        ];

        from(route('calendar-events.index'))
            ->post(route('calendar-events.store'), $eventData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('calendar-events.index'))
            ->assertSessionHas('success');

        assertDatabaseHas('calendar_events', [
            'title' => 'Sunday Service',
            'description' => 'Weekly Sunday morning service',
            'location' => 'Main Sanctuary',
        ]);
    });

    it('can store a calendar event without optional fields', function (): void {
        $eventData = [
            'title' => 'Prayer Meeting',
            'start_at' => now()->addDay()->setTime(19, 0)->toISOString(),
            'end_at' => now()->addDay()->setTime(20, 0)->toISOString(),
        ];

        from(route('calendar-events.index'))
            ->post(route('calendar-events.store'), $eventData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('calendar-events.index'))
            ->assertSessionHas('success');

        assertDatabaseHas('calendar_events', [
            'title' => 'Prayer Meeting',
            'description' => null,
            'location' => null,
        ]);
    });

    it('validates required fields', function (): void {
        from(route('calendar-events.index'))
            ->post(route('calendar-events.store'), [])
            ->assertSessionHasErrors(['title', 'start_at', 'end_at']);
    });

    it('validates that end date is after or equal to start date', function (): void {
        $eventData = [
            'title' => 'Invalid Event',
            'start_at' => now()->addDay()->setTime(9, 0)->toISOString(),
            'end_at' => now()->setTime(10, 0)->toISOString(), // End is before start
        ];

        from(route('calendar-events.index'))
            ->post(route('calendar-events.store'), $eventData)
            ->assertSessionHasErrors(['end_at']);
    });

    it('can store an all-day event', function (): void {
        $eventData = [
            'title' => 'Church Picnic',
            'description' => 'Annual church picnic',
            'location' => 'Central Park',
            'start_at' => now()->addDays(7)->setTime(0, 0)->toISOString(),
            'end_at' => now()->addDays(7)->setTime(23, 45)->toISOString(),
        ];

        from(route('calendar-events.index'))
            ->post(route('calendar-events.store'), $eventData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('calendar-events.index'))
            ->assertSessionHas('success');

        assertDatabaseHas('calendar_events', [
            'title' => 'Church Picnic',
        ]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot store a calendar event', function (): void {
        $eventData = [
            'title' => 'Sunday Service',
            'start_at' => now()->addDay()->setTime(9, 0)->toISOString(),
            'end_at' => now()->addDay()->setTime(11, 0)->toISOString(),
        ];

        post(route('calendar-events.store'), $eventData)
            ->assertStatus(403);
    });
});
