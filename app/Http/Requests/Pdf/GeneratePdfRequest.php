<?php

declare(strict_types=1);

namespace App\Http\Requests\Pdf;

use App\Enums\PdfFormat;
use App\Enums\PdfOrientation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class GeneratePdfRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rows' => ['array'],
            'unSelectedColumns' => ['array'],
            'orientation' => [Rule::enum(PdfOrientation::class)],
            'format' => [Rule::enum(PdfFormat::class)],
        ];
    }

    /**
     * Get the rows selected for the PDF generation.
     *
     * @return array<string>|array{}
     */
    public function getRows(): array
    {
        if (! $this->has('rows')) {
            return [];
        }

        /**
         * @var array<string> $rows
         */
        $rows = $this->array('rows');

        return $rows;
    }

    /**
     * Get the unselected columns for the PDF generation.
     *
     * @return array<string>|array{}
     */
    public function getUnSelectedColumns(): array
    {
        if (! $this->has('unSelectedColumns')) {
            return [];
        }

        /**
         * @var array<string> $unSelectedColumns
         */
        $unSelectedColumns = $this->array('unSelectedColumns');

        return $unSelectedColumns;
    }

    /**
     * Get the PDF orientation.
     */
    public function getPdfOrientation(): PdfOrientation
    {
        return $this->enum('orientation', PdfOrientation::class, PdfOrientation::PORTRAIT);
    }

    /**
     * Get the PDF format.
     */
    public function getPdfFormat(): PdfFormat
    {
        return $this->enum('format', PdfFormat::class, PdfFormat::A4);
    }
}
