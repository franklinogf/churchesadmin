<?php

declare(strict_types=1);

namespace App\Http\Requests\Wallet;

use App\Models\ChurchWallet;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class StoreWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('create', ChurchWallet::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('church_wallets')],
            'description' => ['nullable', 'string', 'min:3', 'max:255'],
            'balance' => ['nullable', 'decimal:2', 'min:1'],
            'bank_name' => ['required', 'string', 'min:3', 'max:255'],
            'bank_routing_number' => ['required', 'string', 'min:3', 'max:255'],
            'bank_account_number' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
}
