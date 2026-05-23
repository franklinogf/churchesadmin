<?php

declare(strict_types=1);

namespace App\Http\Requests\FirstCommunionCertificate;

use App\Models\FirstCommunionCertificate;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @property-read FirstCommunionCertificate $firstCommunionCertificate
 */
final class UpdateFirstCommunionCertificateRequest extends FormRequest
{
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->firstCommunionCertificate);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'priest' => ['nullable', 'string', 'max:255'],
            'communicant_name' => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'communion_at' => ['nullable', 'date'],
            'issued_at' => ['nullable', 'date'],
        ];
    }
}
