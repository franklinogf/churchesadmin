<?php

declare(strict_types=1);

namespace App\Http\Requests\Check;

use App\Enums\CheckType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateCheckRequest extends FormRequest
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
            'wallet_id' => ['required', 'string',
                Rule::exists("$connection.wallets", 'slug')
                    ->where('holder_id', (string) $tenantId),
            ],
            'member_id' => ['required', 'string',
                Rule::exists('members', 'id'),
            ],
            'amount' => ['required', 'decimal:2', 'min:1'],
            'date' => ['required', 'date:Y-m-d'],
            'type' => ['required', 'string', Rule::enum(CheckType::class)],
            'confirmed' => ['required', 'boolean'],
        ];
    }
}
