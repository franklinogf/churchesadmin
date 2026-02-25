<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\Enums\LanguageCode;
use App\Enums\TenantPermission;
use App\Models\TenantUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(#[CurrentUser] TenantUser $user): bool
    {
        return $user->can(TenantPermission::SETTINGS_CHANGE_LANGUAGE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'locale' => ['required', 'string', Rule::enum(LanguageCode::class)],
        ];
    }
}
