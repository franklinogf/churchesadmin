<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CivilStatus;
use App\Enums\FlashMessageKey;
use App\Enums\Gender;
use App\Enums\TagType;
use App\Http\Requests\CreateMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\TagResource;
use App\Models\Member;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class MemberController extends Controller
{
    public function __construct(#[CurrentUser] protected User $user)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $members = Member::latest()->get();

        return Inertia::render('members/index', ['members' => $members]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response|RedirectResponse
    {
        $response = Gate::inspect('create', Member::class);

        if ($response->denied()) {
            return to_route('members.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $genders = Gender::options();
        $civilStatuses = CivilStatus::options();
        $skills = Tag::getWithType(TagType::SKILL->value);
        $categories = Tag::getWithType(TagType::CATEGORY->value);

        return Inertia::render('members/create', [
            'genders' => $genders,
            'civilStatuses' => $civilStatuses,
            'skills' => TagResource::collection($skills),
            'categories' => TagResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMemberRequest $request): RedirectResponse
    {
        $response = Gate::inspect('create', Member::class);

        if ($response->denied()) {
            return to_route('members.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $skills = collect($request->safe()->only(['skills']))->flatten()->toArray();
        $categories = collect($request->safe()->only(['categories']))->flatten()->toArray();
        /**
         * @var array<string, mixed> $validated
         */
        $validated = $request->safe()->except(['skills', 'categories']);
        $member = Member::create($validated);
        $member->attachTags($skills, TagType::SKILL->value);
        $member->attachTags($categories, TagType::CATEGORY->value);

        return to_route('members.index')->with(FlashMessageKey::SUCCESS->value, 'Member created successfully.');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Member $member): void
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member): Response|RedirectResponse
    {
        $response = Gate::inspect('update', $member);

        if ($response->denied()) {
            return to_route('members.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $genders = Gender::options();
        $civilStatuses = CivilStatus::options();
        $skills = Tag::getWithType(TagType::SKILL->value);
        $categories = Tag::getWithType(TagType::CATEGORY->value);

        return Inertia::render('members/edit', [
            'member' => new MemberResource($member),
            'genders' => $genders,
            'civilStatuses' => $civilStatuses,
            'skills' => TagResource::collection($skills),
            'categories' => TagResource::collection($categories),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $response = Gate::inspect('update', $member);

        if ($response->denied()) {
            return to_route('members.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $skills = collect($request->safe()->only(['skills']))->flatten()->toArray();
        $categories = collect($request->safe()->only(['categories']))->flatten()->toArray();
        /**
         * @var array<string, mixed> $validated
         */
        $validated = $request->safe()->except(['skills', 'categories']);
        $member->update($validated);
        $member->syncTagsWithType($skills, TagType::SKILL->value);
        $member->syncTagsWithType($categories, TagType::CATEGORY->value);

        return to_route('members.index')->with(FlashMessageKey::SUCCESS->value, 'Member updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member): RedirectResponse
    {
        $response = Gate::inspect('delete', $member);

        if ($response->denied()) {

            return to_route('members.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $member->delete();

        return to_route('members.index')->with(FlashMessageKey::SUCCESS->value, 'Member deleted successfully.');
    }
}
