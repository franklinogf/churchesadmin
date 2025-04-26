<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag\Skill;

use App\Enums\TagType;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read \App\Models\Tag $tag
 */
final class UpdateSkillRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:255',
                UniqueTranslationRule::for('tags')
                    ->ignore($this->tag->id)
                    ->where('type', TagType::SKILL->value),
            ],
            'is_regular' => ['required', 'boolean'],
        ];
    }
}
