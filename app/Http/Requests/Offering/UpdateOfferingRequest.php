<?php

declare(strict_types=1);

namespace App\Http\Requests\Offering;

use App\Enums\PaymentMethod;
use App\Rules\SelectOptionWithModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateOfferingRequest extends FormRequest
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
            'payer_id' => ['required', Rule::anyOf([
                Rule::exists('members', 'id'),
                Rule::in(['non_member']),
            ])],
            'date' => ['required', 'date:Y-m-d'],

            'wallet_id' => ['required', 'string',
                Rule::exists("$connection.wallets", 'id')
                    ->where('holder_id', (string) $tenantId),
            ],
            'payment_method' => ['required', 'string', Rule::enum(PaymentMethod::class)],
            'offering_type' => ['required', new SelectOptionWithModel],
            'amount' => ['required', 'decimal:2', 'min:1'],
            'note' => ['nullable', 'string', 'min:3', 'max:255'],

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
            'payer_id' => mb_strtolower(__('Payer')),
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
