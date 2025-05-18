<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag\Category;

use App\Enums\TagType;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @property-read \App\Models\Tag $tag
 */
final class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->tag);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255',
                UniqueTranslationRule::for('tags')
                    ->ignore($this->tag->id)
                    ->where('type', TagType::CATEGORY->value),
            ],
            'is_regular' => ['required', 'boolean'],
        ];
    }
}
