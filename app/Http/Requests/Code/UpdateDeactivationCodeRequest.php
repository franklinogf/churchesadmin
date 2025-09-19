<?php

declare(strict_types=1);

namespace App\Http\Requests\Code;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read \App\Models\DeactivationCode $deactivationCode
 */
final class UpdateDeactivationCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->deactivationCode);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('deactivation_codes')->ignore($this->deactivationCode->id)],
        ];
    }
}
