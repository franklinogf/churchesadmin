<?php

declare(strict_types=1);

namespace App\Http\Requests\ConfirmationCertificate;

use App\Models\ConfirmationCertificate;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read ConfirmationCertificate $confirmationCertificate
 */
final class UpdateConfirmationCertificateRequest extends FormRequest
{
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->confirmationCertificate);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var ConfirmationCertificate $confirmationCertificate */
        $confirmationCertificate = $this->confirmationCertificate;

        return [
            'book' => ['required', 'numeric'],
            'folio' => ['required', 'numeric'],
            'priest' => ['nullable', 'string', 'max:255'],
            'confirmed_name' => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'confirmed_by' => ['nullable', 'string', 'max:255'],
            'confirmed_at' => ['nullable', 'date'],
            'godfather_name' => ['nullable', 'string', 'max:255'],
            'godmother_name' => ['nullable', 'string', 'max:255'],
            'issued_place' => ['nullable', 'string', 'max:255'],
            'issued_at' => ['nullable', 'date'],
            'marginal_note' => ['nullable', 'string'],
            'record_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique(ConfirmationCertificate::class)->where(fn (Builder $query): Builder => $query
                    ->where('book', $this->string('book')->value())
                    ->where('folio', $this->string('folio')->value()))
                    ->ignore($confirmationCertificate->id),
            ],
        ];
    }
}
