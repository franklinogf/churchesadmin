<?php

declare(strict_types=1);

namespace App\Http\Requests\Check;

use App\Models\Check;
use Closure;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

final class ConfirmMultipleCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('confirm', Check::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'checks' => ['required', 'array'],
            'checks.*' => ['required', 'exists:checks,id'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     *
     * @return array<int, Closure>
     */
    public function after(): array
    {

        return [
            function (Validator $validator): void {
                $checkIds = $this->array('checks');
                if (Check::whereIn('id', $checkIds)->whereNull('check_number')->exists()) {

                    $validator->errors()->add(
                        'checks',
                        'All checks must have a check number.'
                    );
                }

            },
        ];
    }
}
