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
use App\Http\Resources\Tag\TagResource;
use App\Models\Member;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class MemberController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $members = Member::latest()->get();

        return Inertia::render('members/index', ['members' => MemberResource::collection($members)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response|RedirectResponse
    {

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

        $memberData = $request->safe()->except(['skills', 'categories', 'address']);

        $skills = collect($request->safe()->only(['skills']))->flatten()->toArray();
        $categories = collect($request->safe()->only(['categories']))->flatten()->toArray();
        $addressData = $request->safe()->only(['address']);

        $member = Member::create($memberData);

        $member->attachTags($skills, TagType::SKILL->value);
        $member->attachTags($categories, TagType::CATEGORY->value);

        if (array_key_exists('address', $addressData)) {
            $member->address()->create($addressData['address']);
        }

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

        $memberData = $request->safe()->except(['skills', 'categories', 'address']);

        $skills = collect($request->safe()->only(['skills']))->flatten()->toArray();
        $categories = collect($request->safe()->only(['categories']))->flatten()->toArray();
        $addressData = $request->safe()->only(['address']);

        $member->update($memberData);

        $member->syncTagsWithType($skills, TagType::SKILL->value);
        $member->syncTagsWithType($categories, TagType::CATEGORY->value);

        if (array_key_exists('address', $addressData)) {
            $member->address()->update($addressData['address']);
        }

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
