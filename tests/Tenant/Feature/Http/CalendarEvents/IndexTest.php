<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\CalendarEvent;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('calendar-events.index'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CALENDAR_EVENTS_MANAGE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('calendar-events.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('main/calendar/index')
                ->has('events')
            );
    });

    it('displays calendar events in the list', function (): void {
        CalendarEvent::factory()->count(3)->create();

        get(route('calendar-events.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('main/calendar/index')
                ->has('events', 3)
            );
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the calendar events index', function (): void {
        get(route('calendar-events.index'))
            ->assertStatus(403);
    });
});
