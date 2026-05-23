<?php

declare(strict_types=1);

namespace App\Http\Requests\MarriageCertificate;

use App\Models\MarriageCertificate;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read MarriageCertificate $marriageCertificate
 */
final class UpdateMarriageCertificateRequest extends FormRequest
{
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->marriageCertificate);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var MarriageCertificate $marriageCertificate */
        $marriageCertificate = $this->marriageCertificate;

        return [
            'book' => ['required', 'numeric'],
            'folio' => ['required', 'numeric'],
            'married_at' => ['nullable', 'date'],
            'priest' => ['nullable', 'string', 'max:255'],
            'groom_name' => ['required', 'string', 'max:255'],
            'groom_age' => ['nullable', 'string', 'max:255'],
            'groom_birthplace' => ['nullable', 'string', 'max:255'],
            'groom_residence' => ['nullable', 'string', 'max:255'],
            'groom_father_name' => ['nullable', 'string', 'max:255'],
            'groom_mother_name' => ['nullable', 'string', 'max:255'],
            'bride_name' => ['required', 'string', 'max:255'],
            'bride_age' => ['nullable', 'string', 'max:255'],
            'bride_birthplace' => ['nullable', 'string', 'max:255'],
            'bride_residence' => ['nullable', 'string', 'max:255'],
            'bride_father_name' => ['nullable', 'string', 'max:255'],
            'bride_mother_name' => ['nullable', 'string', 'max:255'],
            'witness1_name' => ['nullable', 'string', 'max:255'],
            'witness2_name' => ['nullable', 'string', 'max:255'],
            'issued_at' => ['nullable', 'date'],
            'marginal_note' => ['nullable', 'string'],
            'record_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique(MarriageCertificate::class)->where(fn (Builder $query): Builder => $query
                    ->where('book', $this->string('book')->value())
                    ->where('folio', $this->string('folio')->value()))
                    ->ignore($marriageCertificate->id),
            ],
        ];
    }
}
