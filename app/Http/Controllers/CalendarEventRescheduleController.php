<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CalendarEvent\RescheduleCalendarEventAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\RescheduleCalendarEventRequest;
use App\Models\CalendarEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CalendarEventRescheduleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RescheduleCalendarEventRequest $request, CalendarEvent $calendarEvent, RescheduleCalendarEventAction $action): RedirectResponse
    {
        $action->handle(
            $calendarEvent,
            $request->validated()
        );

        return back()->with(FlashMessageKey::SUCCESS->value, __('Calendar event rescheduled successfully.'));
    }
}
