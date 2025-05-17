<?php

declare(strict_types=1);

namespace App\Http\Requests\Check;

use App\Enums\CheckLayoutField;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateCheckLayoutRequest extends FormRequest
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
            'fields' => ['required', 'array'],
            ...collect(CheckLayoutField::cases())
                ->mapWithKeys(fn (CheckLayoutField $field) => ["fields.{$field->value}" => ['required']])
                ->toArray(),
            'fields.*.position.x' => ['required', 'numeric'],
            'fields.*.position.y' => ['required', 'numeric'],
            'width' => ['required', 'integer'],
            'height' => ['required', 'integer'],
        ];
    }
}
