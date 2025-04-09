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
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $categories = Tag::whereType(TagType::CATEGORY->value)->orderBy('created_at', 'desc')->get();

        return Inertia::render('categories/index', [
            'categories' => TagResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $response = Gate::inspect('create', Tag::class);

        if ($response->denied()) {
            return to_route('categories.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        /**
         * @var array{name:string,is_regular:bool}
         */
        $validated = $request->validate([
            'name.*' => ['required', 'string', 'min:3', 'max:255', UniqueTranslationRule::for('tags')->where('type', TagType::CATEGORY->value)],
            'is_regular' => ['required', 'boolean'],
        ]);

        Tag::create([
            'name' => $validated['name'],
            'type' => TagType::CATEGORY->value,
            'is_regular' => $validated['is_regular'],
        ]);

        return to_route('categories.index')->with(FlashMessageKey::SUCCESS->value, __('Category created successfully.'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);
        $response = Gate::inspect('update', $tag);

        if ($response->denied()) {
            return to_route('categories.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        /**
         * @var array{name:string,is_regular:bool}
         */
        $validated = $request->validate([
            'name.*' => ['required', 'string', 'min:3', 'max:255',
                UniqueTranslationRule::for('tags')
                    ->ignore($tag->id)
                    ->where('type', TagType::CATEGORY->value),
            ],
            'is_regular' => ['required', 'boolean'],
        ]);

        $tag->update([
            'name' => $validated['name'],
            'is_regular' => $validated['is_regular'],
        ]);

        return to_route('categories.index')->with(FlashMessageKey::SUCCESS->value, __('Category updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);
        $response = Gate::inspect('delete', $tag);

        if ($response->denied()) {
            return to_route('categories.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }
        $tag->delete();

        return to_route('categories.index')->with(FlashMessageKey::SUCCESS->value, __('Category deleted successfully.'));
    }
}
