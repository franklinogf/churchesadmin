<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Tag\CreateTagAction;
use App\Actions\Tag\DeleteTagAction;
use App\Actions\Tag\UpdateTagAction;
use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Http\Requests\Tag\Category\StoreCategoryRequest;
use App\Http\Requests\Tag\Category\UpdateCategoryRequest;
use App\Http\Resources\Tag\TagResource;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
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
        Gate::authorize('viewAny', [Tag::class, TagType::CATEGORY]);

        $categories = Tag::whereType(TagType::CATEGORY->value)->orderBy('created_at', 'desc')->get();

        return Inertia::render('main/categories/index', [
            'categories' => TagResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request, CreateTagAction $action): RedirectResponse
    {
        /**
         * @var array{name:string,is_regular:bool} $data
         */
        $data = $request->validated();

        Gate::authorize('create', [Tag::class, $data['is_regular'], TagType::CATEGORY]);

        $action->handle($data, TagType::CATEGORY);

        return to_route('categories.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Category')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Tag $tag, UpdateTagAction $action): RedirectResponse
    {
        Gate::authorize('update', $tag);

        $action->handle($tag, [
            'name' => $request->string('name')->value(),
            'is_regular' => $request->boolean('is_regular'),
        ]);

        return to_route('categories.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Category')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag, DeleteTagAction $action): RedirectResponse
    {
        Gate::authorize('delete', $tag);

        $action->handle($tag);

        return to_route('categories.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Category')])
        );
    }
}
