<?php

declare(strict_types=1);

namespace App\Http\Requests\Wallet;

use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreWalletRequest extends FormRequest
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
        $connection = config('tenancy.database.central_connection');

        return [
            'name.*' => ['required', 'string', 'min:3', 'max:255', UniqueTranslationRule::for("{$connection}.wallets")->where('holder_id', tenant('id'))],
            'description.*' => ['nullable', 'string', 'min:3', 'max:255'],
        ];
    }
}
