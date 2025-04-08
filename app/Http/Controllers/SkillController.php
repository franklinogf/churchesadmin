<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $skills = Tag::whereType(TagType::SKILL->value)->orderBy('created_at', 'desc')->get();

        return Inertia::render('skills/index', [
            'skills' => TagResource::collection($skills),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        /**
         * @var array{name:string,is_regular:bool}
         */
        $validated = $request->validate([
            'name.*' => ['required', 'string', 'min:3', 'max:255', UniqueTranslationRule::for('tags')->where('type', TagType::SKILL->value)],
            'is_regular' => ['required', 'boolean'],
        ]);

        Tag::create([
            'name' => $validated['name'],
            'type' => TagType::SKILL->value,
            'is_regular' => $validated['is_regular'],
        ]);

        return to_route('skills.index')->with(FlashMessageKey::SUCCESS->value, __('Skill created successfully.'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);

        if ($tag->is_regular) {
            return to_route('skills.index')->with(FlashMessageKey::ERROR->value, __('Cannot update a regular skill.'));
        }

        /**
         * @var array{name:string,is_regular:bool}
         */
        $validated = $request->validate([
            'name.*' => ['required', 'string', 'min:3', 'max:255',
                UniqueTranslationRule::for('tags')
                    ->ignore($tag->id)
                    ->where('type', TagType::SKILL->value),
            ],
            'is_regular' => ['required', 'boolean'],
        ]);

        $tag->update([
            'name' => $validated['name'],
            'is_regular' => $validated['is_regular'],
        ]);

        return to_route('skills.index')->with(FlashMessageKey::SUCCESS->value, __('Skill updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);
        if ($tag->is_regular) {
            return to_route('skills.index')->with(FlashMessageKey::ERROR->value, __('Cannot delete a regular skill.'));
        }
        $tag->delete();

        return to_route('skills.index')->with(FlashMessageKey::SUCCESS->value, __('Skill deleted successfully.'));
    }
}
