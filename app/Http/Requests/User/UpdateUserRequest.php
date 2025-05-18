<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read \App\Models\TenantUser $user
 */
final class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->user);
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
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'roles' => ['required', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')],
            'additional_permissions' => ['array'],
            'additional_permissions.*' => ['nullable', 'string', Rule::exists('permissions', 'name')],
        ];
    }

    /**
     * Get the validated user data from the request.
     *
     * @return array<string, mixed>
     */
    public function getUserData(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->safe()->except([
            'roles',
            'additional_permissions',
        ]);

        return $data;
    }

    /**
     * Get the validated roles data from the request.
     *
     * @return array<int,string>
     */
    public function getRoleData(): array
    {
        /** @var array<int,string> $data */
        $data = collect($this->safe()->only(['roles']))->flatten()->toArray();

        return $data;
    }

    /**
     * Get the validated additional permissions data from the request.
     *
     * @return array<int,string>
     */
    public function getAdditionalPermissionsData(): array
    {
        /** @var array<int,string> $data */
        $data = collect($this->safe()->only(['additional_permissions']))->flatten()->toArray();

        return $data;
    }
}
