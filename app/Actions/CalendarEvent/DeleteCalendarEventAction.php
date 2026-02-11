<?php

declare(strict_types=1);

namespace App\Actions\CalendarEvent;

use App\Models\CalendarEvent;

final class DeleteCalendarEventAction
{
    /**
     * Handle the deletion of a calendar event.
     */
    public function handle(CalendarEvent $calendarEvent): void
    {
        $calendarEvent->delete();
    }
}
