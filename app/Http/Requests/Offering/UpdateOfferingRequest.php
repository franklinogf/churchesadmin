<?php

declare(strict_types=1);

namespace App\Http\Requests\Offering;

use App\Enums\PaymentMethod;
use App\Rules\SelectOptionWithModel;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read \App\Models\Offering $offering
 */
final class UpdateOfferingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->offering);
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

            'wallet_id' => ['required',
                Rule::exists('church_wallets', 'id'),
            ],
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
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
            'donor_id' => mb_strtolower(__('Payer')),
            'date' => mb_strtolower(__('Date')),
            'offerings' => mb_strtolower(__('Offerings')),
            'offerings.*.wallet_id' => mb_strtolower(__('Wallet')),
            'offerings.*.payment_method' => mb_strtolower(__('Payment Method')),
            'offerings.*.offering_type' => mb_strtolower(__('Offering type')),
            'offerings.*.amount' => mb_strtolower(__('Amount')),
            'offerings.*.note' => mb_strtolower(__('Note')),
        ];
    }
}
