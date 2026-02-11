<?php

declare(strict_types=1);

namespace App\Actions\CalendarEvent;

use App\Models\CalendarEvent;

final class UpdateCalendarEventAction
{
    /**
     * Handle the update of a calendar event.
     *
     * @param  array{title?:string, description?:string|null, location?:string|null, start_at?:string, end_at?:string}  $data
     */
    public function handle(CalendarEvent $calendarEvent, array $data): CalendarEvent
    {
        $calendarEvent->update($data);

        return $calendarEvent->fresh();
    }
}
