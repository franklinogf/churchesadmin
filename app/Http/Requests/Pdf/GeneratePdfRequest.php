<?php

declare(strict_types=1);

namespace App\Http\Requests\Pdf;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rows' => ['array'],
            'unSelectedColumns' => ['array'],
            'orientation' => [Rule::enum(Orientation::class)],
            'format' => [Rule::enum(Format::class)],
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
    public function getPdfOrientation(): Orientation
    {
        $enum = $this->enum('orientation', Orientation::class, Orientation::Portrait);
        if (! $enum instanceof Orientation) {
            throw new InvalidArgumentException('Invalid orientation provided.');
        }

        return $enum;
    }

    /**
     * Get the PDF format.
     */
    public function getPdfFormat(): Format
    {
        $enum = $this->enum('format', Format::class, Format::A4);
        if (! $enum instanceof Format) {
            throw new InvalidArgumentException('Invalid format provided.');
        }

        return $enum;
    }
}
