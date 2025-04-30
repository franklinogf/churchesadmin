<?php

declare(strict_types=1);

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreExpenseRequest extends FormRequest
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
            'expenses' => ['required', 'array', 'min:1'],
            'expenses.*.date' => ['required', 'date:Y-m-d'],
            'expenses.*.wallet_id' => ['required', 'string',
                Rule::exists("$connection.wallets", 'id')
                    ->where('holder_id', $tenantId),
            ],
            'expenses.*.member_id' => ['nullable', 'string',
                Rule::exists('members', 'id'),
            ],
            'expenses.*.expense_type_id' => ['required', 'string',
                Rule::exists('expense_types', 'id'),
            ],

            'expenses.*.amount' => ['required', 'decimal:2', 'min:0.01'],
            'expenses.*.note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
