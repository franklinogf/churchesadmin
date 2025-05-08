<?php

declare(strict_types=1);

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        /**
         * @var string $connection
         */
        $connection = config('tenancy.database.central_connection');
        /**
         * @var string $tenantId
         */
        $tenantId = tenant('id');

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique("{$connection}.wallets")
                ->ignore($this->wallet->id)
                ->where('holder_id', $tenantId)],
            'balance' => ['nullable', 'decimal:2', 'min:1'],
            'description' => ['nullable', 'string', 'min:3', 'max:255'],
            'bank_name' => ['required', 'string', 'min:3', 'max:255'],
            'bank_routing_number' => ['required', 'string', 'min:3', 'max:255'],
            'bank_account_number' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
}
