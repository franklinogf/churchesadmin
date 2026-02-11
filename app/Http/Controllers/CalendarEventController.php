<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CalendarEvent\CreateCalendarEventAction;
use App\Actions\CalendarEvent\DeleteCalendarEventAction;
use App\Actions\CalendarEvent\UpdateCalendarEventAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\StoreCalendarEventRequest;
use App\Http\Requests\UpdateCalendarEventRequest;
use App\Http\Resources\CalendarEventResource;
use App\Models\CalendarEvent;
use App\Models\TenantUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class CalendarEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', CalendarEvent::class);

        $query = CalendarEvent::query()->with('creator');

        // Filter by date range if provided
        if ($request->has('start_date')) {
            $query->where('start_at', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('end_at', '<=', $request->input('end_date'));
        }

        $events = $query->orderBy('start_at', 'desc')->get();

        return Inertia::render('main/calendar/index', [
            'events' => CalendarEventResource::collection($events),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalendarEventRequest $request, CreateCalendarEventAction $action, #[CurrentUser] TenantUser $user): RedirectResponse
    {
        $action->handle(
            $request->validated(),
            $user
        );

        return to_route('calendar-events.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Calendar Event')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCalendarEventRequest $request, CalendarEvent $calendarEvent, UpdateCalendarEventAction $action): RedirectResponse
    {
        $action->handle(
            $calendarEvent,
            $request->validated()
        );

        return to_route('calendar-events.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Calendar Event')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CalendarEvent $calendarEvent, DeleteCalendarEventAction $action): RedirectResponse
    {
        Gate::authorize('delete', $calendarEvent);

        $action->handle($calendarEvent);

        return to_route('calendar-events.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Calendar Event')])
        );
    }
}
