<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag\Skill;

use App\Enums\TagType;
use App\Models\Tag;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

final class StoreSkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('create', [Tag::class, $this->boolean('is_regular'), TagType::SKILL]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', UniqueTranslationRule::for('tags')->where('type', TagType::SKILL->value)],
            'is_regular' => ['required', 'boolean'],
        ];
    }
}
