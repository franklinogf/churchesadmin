<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateGeneralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(#[CurrentUser] TenantUser $user): bool
    {

        return $user->can(TenantPermission::SETTINGS_MANAGE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,svg', 'max:2048'], // 2MB max
        ];
    }
}
