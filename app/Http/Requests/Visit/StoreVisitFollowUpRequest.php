<?php

declare(strict_types=1);

namespace App\Http\Requests\Visit;

use App\Enums\FollowUpType;
use App\Models\Visit;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class StoreVisitFollowUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', Visit::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:members,id'],
            'type' => ['required', 'string', Rule::enum(FollowUpType::class)],
            'follow_up_at' => ['required',  Rule::date()->format('Y-m-d H:i:s')->todayOrBefore()],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
