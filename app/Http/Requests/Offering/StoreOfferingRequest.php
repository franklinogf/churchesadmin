<?php

declare(strict_types=1);

namespace App\Http\Requests\Offering;

use App\Enums\PaymentMethod;
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
            'payer_id' => ['required', Rule::anyOf([
                Rule::exists('members', 'id'),
                Rule::in(['non_member']),
            ])],
            'date' => ['required', 'date:Y-m-d'],
            'offerings' => ['required', 'array', 'min:1'],
            'offerings.*.wallet_id' => ['required', 'string',
                Rule::exists("$connection.wallets", 'id')
                    ->where('holder_id', (string) tenant('id')),
            ],
            'offerings.*.payment_method' => ['required', 'string', Rule::enum(PaymentMethod::class)],
            'offerings.*.recipient_id' => ['nullable', Rule::exists('missionaries', 'id')],
            'offerings.*.offering_type_id' => ['required', 'string', Rule::exists('offering_types', 'id')],
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
            'message' => mb_strtolower(__('Message')),
            'date' => mb_strtolower(__('Date')),
            'offeringType' => mb_strtolower(__('Offering Type')),
            'payer_id' => mb_strtolower(__('Payer')),
            'offerings' => mb_strtolower(__('Offerings')),
            'offerings.*.wallet_id' => mb_strtolower(__('Wallet')),
            'offerings.*.payment_method' => mb_strtolower(__('Payment Method')),
            'offerings.*.recipient_id' => mb_strtolower(__('Recipient')),
            'offerings.*.offering_type_id' => mb_strtolower(__('Offering Type')),
            'offerings.*.amount' => mb_strtolower(__('Amount')),
            'offerings.*.note' => mb_strtolower(__('Note')),
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {

        $this->replace(['payer_id' => null]);

    }
}
