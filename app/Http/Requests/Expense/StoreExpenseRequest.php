<?php

declare(strict_types=1);

namespace App\Http\Requests\Expense;

use App\Models\Expense;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class StoreExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('create', Expense::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'expenses' => ['required', 'array', 'min:1'],
            'expenses.*.date' => ['required', 'date:Y-m-d'],
            'expenses.*.wallet_id' => ['required', 'string',
                Rule::exists('church_wallets', 'id'),
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
