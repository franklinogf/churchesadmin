<?php

declare(strict_types=1);

namespace App\Http\Requests\Wallet;

use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read \App\Models\Wallet $wallet
 */
final class UpdateWalletRequest extends FormRequest
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
        $connection = (string) config('tenancy.database.central_connection');

        return [
            'name' => ['required', 'array', 'min:2'],
            'name.*' => ['required', 'string', 'min:3', 'max:255', UniqueTranslationRule::for("{$connection}.wallets")
                ->ignore($this->wallet->id)
                ->where('holder_id', (string) tenant('id'))],
            'description' => ['nullable', 'array'],
            'description.*' => ['nullable', 'string', 'min:3', 'max:255'],
            'bank_name' => ['required', 'string', 'min:3', 'max:255'],
            'bank_routing_number' => ['required', 'string', 'min:3', 'max:255'],
            'bank_account_number' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
}
