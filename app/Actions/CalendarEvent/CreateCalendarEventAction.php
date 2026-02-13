<?php

declare(strict_types=1);

namespace App\Actions\CalendarEvent;

use App\Models\CalendarEvent;
use App\Models\TenantUser;

final class CreateCalendarEventAction
{
    /**
     * Handle the creation of a calendar event.
     *
     * @param  array{title:string, description?:string|null, location?:string|null, color:string, start_at:string, end_at:string}  $data
     */
    public function handle(array $data, TenantUser $user): CalendarEvent
    {
        return CalendarEvent::create([
            ...$data,
            'created_by' => $user->id,
        ]);
    }
}
