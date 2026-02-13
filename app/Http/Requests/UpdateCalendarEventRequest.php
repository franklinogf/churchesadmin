<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\CalendarEventColorEnum;
use App\Models\CalendarEvent;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read CalendarEvent $calendar_event
 */
final class UpdateCalendarEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {

        return Gate::authorize('update', $this->calendar_event);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'color' => ['required', Rule::enum(CalendarEventColorEnum::class)],
            'start_at' => ['required', Rule::date()],
            'end_at' => ['required', Rule::date()->after('start_at')],
        ];
    }
}
