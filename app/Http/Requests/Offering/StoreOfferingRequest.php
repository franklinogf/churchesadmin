<?php

declare(strict_types=1);

namespace App\Http\Requests\Offering;

use App\Enums\OfferingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreOfferingRequest extends FormRequest
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
            'message' => ['nullable', 'string', 'min:3', 'max:255'],
            'date' => ['required', 'date:Y-m-d'],
            'offering_type' => ['required', 'string', Rule::enum(OfferingType::class)],
            'payer_id' => ['required', Rule::exists('members', 'id')],
            'offerings' => ['required', 'array', 'min:1'],
            'offerings.*.amount' => ['required', 'decimal:2', 'min:1'],
            'offerings.*.wallet_id' => ['required', 'string',
                Rule::exists("$connection.wallets", 'id')
                    ->where('holder_id', (string) tenant('id')),
            ],

        ];
    }

    public function attributes(): array
    {
        return [
            'message' => mb_strtolower(__('Message')),
            'date' => mb_strtolower(__('Date')),
            'offeringType' => mb_strtolower(__('Offering Type')),
            'payer_id' => mb_strtolower(__('Payer')),
            'offerings' => mb_strtolower(__('Offerings')),
            'offerings.*.amount' => mb_strtolower(__('Amount')),
            'offerings.*.wallet_id' => mb_strtolower(__('Wallet')),
        ];
    }
}
