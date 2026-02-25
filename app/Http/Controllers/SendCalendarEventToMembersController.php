<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Models\CalendarEvent;
use App\Models\Member;
use App\Notifications\CalendarEventNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SendCalendarEventToMembersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CalendarEvent $calendarEvent): RedirectResponse
    {
        $membersWithEmail = Member::whereNotNull('email')->limit(1)->get();

        foreach ($membersWithEmail as $member) {

            $member->notify(new CalendarEventNotification($calendarEvent));
        }

        return back()->with(FlashMessageKey::SUCCESS->value, __('Event sent to all members with an email address.'));

    }
}
