<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

final class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')],
            'additional_permissions' => ['array'],
            'additional_permissions.*' => ['nullable', 'string', Rule::exists('permissions', 'name')],
        ];
    }

    public function getUserData(): array
    {
        return $this->safe()->except([
            'roles',
            'additional_permissions',
        ]);
    }

    public function getRoleData(): array
    {
        return collect($this->safe()->only(['roles']))->flatten()->toArray();
    }

    public function getAdditionalPermissionsData(): array
    {
        return collect($this->safe()->only(['additional_permissions']))->flatten()->toArray();
    }
}
