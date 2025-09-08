<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings;

use App\Enums\TenantRole;
use App\Models\TenantUser;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /**
         * @var TenantUser $user
         */
        $user = $this->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(TenantUser::class)->ignore($user->id),
            ],
            'timezone' => [
                'required',
                'string',
                'timezone:all',
            ],
            'timezone_country' => [
                'required',
                'string',
                'min:2',
                'uppercase',
                'max:2',
            ],
            'current_year_id' => [
                'required',
                Rule::excludeIf(
                    ! $user->hasRole(TenantRole::SUPER_ADMIN->value)
                ),
                'exists:current_years,id',
            ],
        ];
    }
}
