<?php

declare(strict_types=1);

namespace App\Http\Requests\Check;

use App\Models\Check;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

final class GenerateCheckNumberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'checks' => ['required', 'array'],
            'checks.*.id' => ['required', 'exists:checks,id'],
            'initial_check_number' => ['required', 'numeric', 'min:1',
                function (string $attribute, string $value, Closure $fail): void {
                    if (Check::confirmed()->where('check_number', $value)->exists()) {
                        $fail('There is already a check with this number.');
                    }
                },
            ],
        ];
    }
}
