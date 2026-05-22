<?php

declare(strict_types=1);

namespace App\Http\Requests\BaptismCertificate;

use App\Models\BaptismCertificate;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class UpdateBaptismCertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->baptismCertificate);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var BaptismCertificate $baptismCertificate */
        $baptismCertificate = $this->baptismCertificate;

        return [
            'book' => ['required', 'numeric'],
            'folio' => ['required', 'numeric'],
            'baptized_name' => ['required', 'string', 'max:255'],
            'baptized_at' => ['nullable', 'date'],
            'priest' => ['nullable', 'string', 'max:255'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_origin_place' => ['nullable', 'string', 'max:255'],
            'father_residence_place' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_origin_place' => ['nullable', 'string', 'max:255'],
            'mother_residence_place' => ['nullable', 'string', 'max:255'],
            'paternal_grandfather_name' => ['nullable', 'string', 'max:255'],
            'paternal_grandmother_name' => ['nullable', 'string', 'max:255'],
            'maternal_grandfather_name' => ['nullable', 'string', 'max:255'],
            'maternal_grandmother_name' => ['nullable', 'string', 'max:255'],
            'godfather_name' => ['nullable', 'string', 'max:255'],
            'godmother_name' => ['nullable', 'string', 'max:255'],
            'issued_place' => ['nullable', 'string', 'max:255'],
            'issued_at' => ['nullable', 'date'],
            'marginal_note' => ['nullable', 'string'],
            'record_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique(BaptismCertificate::class)->where(fn ($query) => $query
                    ->where('book', $this->string('book')->value())
                    ->where('folio', $this->string('folio')->value()))
                    ->ignore($baptismCertificate->id),
            ],
        ];
    }
}
