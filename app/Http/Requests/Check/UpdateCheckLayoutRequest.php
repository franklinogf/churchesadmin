<?php

declare(strict_types=1);

namespace App\Http\Requests\Check;

use App\Enums\CheckLayoutField;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class UpdateCheckLayoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->route('checkLayout'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.target' => ['required', 'string', Rule::enum(CheckLayoutField::class)],
            'fields.*.position.x' => ['required', 'numeric'],
            'fields.*.position.y' => ['required', 'numeric'],
            'width' => ['required', 'integer'],
            'height' => ['required', 'integer'],
        ];
    }
}
