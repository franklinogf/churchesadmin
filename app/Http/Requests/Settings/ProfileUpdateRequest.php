<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings;

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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(TenantUser::class)->ignore($this->user()?->id),
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
        ];
    }
}
