<?php

declare(strict_types=1);

namespace App\Http\Requests;

use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreOfferingTypeRequest extends FormRequest
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
            'name.*' => ['required', 'string', 'max:255', UniqueTranslationRule::for('offering_types')],
        ];
    }
}
