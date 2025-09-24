<?php

declare(strict_types=1);

namespace App\Http\Requests\Member;

use App\Enums\TenantPermission;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @property-read \App\Models\Member $member
 */
final class DeactivateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize(TenantPermission::MEMBERS_DEACTIVATE->value, $this->member);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'deactivation_code_id' => ['required', 'integer', 'exists:deactivation_codes,id'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'deactivation_code_id.required' => __('validation.required', ['attribute' => __('Deactivation Code')]),
            'deactivation_code_id.exists' => __('validation.exists', ['attribute' => __('Deactivation Code')]),
        ];
    }
}
