<?php

declare(strict_types=1);

namespace App\Http\Requests\Check;

use App\Enums\CheckType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCheckRequest extends FormRequest
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
            'wallet_id' => ['required', 'string',
                Rule::exists('church_wallets', 'id'),
            ],
            'member_id' => ['required', 'string',
                Rule::exists('members', 'id'),
            ],
            'amount' => ['required', 'decimal:2', 'min:1'],
            'date' => ['required', 'date:Y-m-d'],
            'type' => ['required', 'string', Rule::enum(CheckType::class)],
            'note' => ['nullable', 'string', 'min:1', 'max:255'],
            'expense_type_id' => [Rule::exists('expense_types', 'id')],
        ];
    }
}
