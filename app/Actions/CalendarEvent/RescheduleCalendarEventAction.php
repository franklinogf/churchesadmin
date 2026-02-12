<?php

declare(strict_types=1);

namespace App\Actions\CalendarEvent;

use App\Models\CalendarEvent;

final class RescheduleCalendarEventAction
{
    /**
     * Handle the update of a calendar event.
     *
     * @param  array{start_at?:string, end_at?:string}  $data
     */
    public function handle(CalendarEvent $calendarEvent, array $data): CalendarEvent
    {
        $calendarEvent->update($data);

        return $calendarEvent->fresh();

    }
}
