<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Tag\CreateTagAction;
use App\Actions\Tag\DeleteTagAction;
use App\Actions\Tag\UpdateTagAction;
use App\Enums\FlashMessageKey;
use App\Enums\TagType;
use App\Http\Requests\Tag\Skill\StoreSkillRequest;
use App\Http\Requests\Tag\Skill\UpdateSkillRequest;
use App\Http\Resources\Tag\TagResource;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', [Tag::class, TagType::SKILL]);

        $skills = Tag::whereType(TagType::SKILL->value)->orderByDesc('order_column')->get();

        return Inertia::render('main/skills/index', [
            'skills' => TagResource::collection($skills),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSkillRequest $request, CreateTagAction $action): RedirectResponse
    {
        /**
         * @var array{name:string,is_regular:bool} $data
         */
        $data = $request->validated();

        Gate::authorize('create', [Tag::class, $data['is_regular'], TagType::SKILL]);

        $action->handle($data, TagType::SKILL);

        return to_route('skills.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Skill')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSkillRequest $request, Tag $tag, UpdateTagAction $action): RedirectResponse
    {
        Gate::authorize('update', $tag);

        $action->handle($tag, [
            'name' => $request->string('name')->value(),
            'is_regular' => $request->boolean('is_regular'),
        ]);

        return to_route('skills.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Skill')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag, DeleteTagAction $action): RedirectResponse
    {
        Gate::authorize('delete', $tag);

        $action->handle($tag);

        return to_route('skills.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Skill')])
        );
    }
}
