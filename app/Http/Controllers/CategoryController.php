<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Tag\CreateTagAction;
use App\Actions\Tag\DeleteTagAction;
use App\Actions\Tag\UpdateTagAction;
use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Http\Requests\Tag\Category\CreateCategoryRequest;
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
    public function index(): Response|RedirectResponse
    {
        $response = Gate::inspect('viewAny', [Tag::class, TagType::CATEGORY]);

        if ($response->denied()) {
            return to_route('dashboard')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $categories = Tag::whereType(TagType::CATEGORY->value)->orderBy('created_at', 'desc')->get();

        return Inertia::render('categories/index', [
            'categories' => TagResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request, CreateTagAction $action): RedirectResponse
    {

        /**
         * @var array{name:string,is_regular:bool} $data
         */
        $data = $request->validated();

        $response = Gate::inspect('create', [Tag::class, $data['is_regular'], TagType::CATEGORY]);

        if ($response->denied()) {
            return to_route('categories.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $action->handle($data, TagType::CATEGORY);

        return to_route('categories.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.store', ['model' => __('Category')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Tag $tag, UpdateTagAction $action): RedirectResponse
    {
        $response = Gate::inspect('update', $tag);

        if ($response->denied()) {
            return to_route('categories.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $action->handle($tag, $request->validated());

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
        $response = Gate::inspect('delete', $tag);

        if ($response->denied()) {
            return to_route('categories.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $action->handle($tag);

        return to_route('categories.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.delete', ['model' => __('Category')])
        );
    }
}
