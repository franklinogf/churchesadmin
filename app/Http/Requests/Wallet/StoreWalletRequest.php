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
        /**
         * @var string $connection
         */
        $connection = config('tenancy.database.central_connection');
        /**
         * @var string $tenantId
         */
        $tenantId = tenant('id');

        return [
            'name' => ['required', 'array'],
            'name.*' => ['required', 'string', 'min:3', 'max:255', UniqueTranslationRule::for("{$connection}.wallets")->where('holder_id', $tenantId)],
            'description' => ['nullable', 'array'],
            'description.*' => ['nullable', 'string', 'min:3', 'max:255'],
            'balance' => ['required', 'decimal:2', 'min:0'],
            'bank_name' => ['required', 'string', 'min:3', 'max:255'],
            'bank_routing_number' => ['required', 'string', 'min:3', 'max:255'],
            'bank_account_number' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
}
