<?php

declare(strict_types=1);

namespace App\Http\Requests\Negativa;

use App\Models\Negativa;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

final class StoreNegativaRequest extends FormRequest
{
    public function authorize(): Response
    {
        return Gate::authorize('create', Negativa::class);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'person_name' => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'searched_from' => ['nullable', 'date'],
            'searched_to' => ['nullable', 'date'],
            'priest' => ['nullable', 'string', 'max:255'],
            'issued_at' => ['nullable', 'date'],
        ];
    }
}
