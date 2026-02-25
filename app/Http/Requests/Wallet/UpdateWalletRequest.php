<?php

declare(strict_types=1);

namespace App\Http\Requests\Wallet;

use App\Models\ChurchWallet;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read ChurchWallet $wallet
 */
final class UpdateWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->route('wallet'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('church_wallets')->ignore($this->wallet->id),
            ],
            'balance' => ['nullable', 'decimal:2', 'min:0'],
            'description' => ['nullable', 'string', 'min:3', 'max:255'],
            'bank_name' => ['required', 'string', 'min:3', 'max:255'],
            'bank_routing_number' => ['required', 'string', 'min:3', 'max:255'],
            'bank_account_number' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
}
