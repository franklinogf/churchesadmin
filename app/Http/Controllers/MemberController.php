<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Member\CreateMemberAction;
use App\Actions\Member\DeleteMemberAction;
use App\Actions\Member\ForceDeleteMemberAction;
use App\Actions\Member\RestoreMemberAction;
use App\Actions\Member\UpdateMemberAction;
use App\Enums\CivilStatus;
use App\Enums\FlashMessageKey;
use App\Enums\Gender;
use App\Enums\TagType;
use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
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
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Member::class);

        $members = Member::latest()->get();

        return Inertia::render('members/index', ['members' => MemberResource::collection($members)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Member::class);

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
    public function store(StoreMemberRequest $request, CreateMemberAction $action): RedirectResponse
    {
        Gate::authorize('create', Member::class);

        $action->handle($request->getMemberData(), $request->getSkillData(), $request->getCategoryData(), $request->getAddressData());

        return to_route('members.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Member')])
        );
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
    public function edit(Member $member): Response
    {
        Gate::authorize('update', $member);

        $member->load('address');
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
    public function update(UpdateMemberRequest $request, Member $member, UpdateMemberAction $action): RedirectResponse
    {
        Gate::authorize('update', $member);

        $data = $request->getMemberData();
        $skills = $request->getSkillData();
        $categories = $request->getCategoryData();
        $address = $request->getAddressData();

        $action->handle($member, $data, $skills, $categories, $address);

        return to_route('members.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Member')])
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member, DeleteMemberAction $action): RedirectResponse
    {
        Gate::authorize('delete', $member);

        $action->handle($member);

        return to_route('members.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Member')])
        );
    }

    public function restore(Member $member, RestoreMemberAction $action): RedirectResponse
    {
        Gate::authorize('restore', $member);

        $action->handle($member);

        return to_route('members.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.restored', ['model' => __('Member')])
        );
    }

    public function forceDelete(Member $member, ForceDeleteMemberAction $action): RedirectResponse
    {
        Gate::authorize('forceDelete', $member);

        $action->handle($member);

        return to_route('members.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Member')])
        );
    }
}
