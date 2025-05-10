<?php

declare(strict_types=1);

namespace App\Http\Requests\Offering;

use App\Enums\PaymentMethod;
use App\Rules\SelectOptionWithModel;
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
        return [
            'donor_id' => ['nullable', Rule::exists('members', 'id')],
            'date' => ['required', 'date:Y-m-d'],
            'offerings' => ['required', 'array', 'min:1'],
            'offerings.*.wallet_id' => ['required', 'string',
                Rule::exists('church_wallets', 'id'),

            ],
            'offerings.*.payment_method' => ['required', 'string', Rule::enum(PaymentMethod::class)],
            'offerings.*.offering_type' => ['required', new SelectOptionWithModel],
            'offerings.*.amount' => ['required', 'decimal:2', 'min:1'],
            'offerings.*.note' => ['nullable', 'string', 'min:3', 'max:255'],

        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'donor_id' => mb_strtolower(__('Payer')),
            'date' => mb_strtolower(__('Date')),
            'offerings' => mb_strtolower(__('Offerings')),
            'offerings.*.wallet_id' => mb_strtolower(__('Wallet')),
            'offerings.*.payment_method' => mb_strtolower(__('Payment Method')),
            'offerings.*.offering_type' => mb_strtolower(__('Offering Type')),
            'offerings.*.amount' => mb_strtolower(__('Amount')),
            'offerings.*.note' => mb_strtolower(__('Note')),
        ];
    }
}
