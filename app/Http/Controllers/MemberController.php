<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Member\CreateMemberAction;
use App\Actions\Member\DeleteMemberAction;
use App\Actions\Member\ForceDeleteMemberAction;
use App\Actions\Member\RestoreMemberAction;
use App\Actions\Member\UpdateMemberAction;
use App\Actions\Visit\TransferVisitToMemberAction;
use App\Enums\CivilStatus;
use App\Enums\FlashMessageKey;
use App\Enums\Gender;
use App\Enums\TagType;
use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Tag\TagResource;
use App\Http\Resources\Visit\VisitResource;
use App\Models\Member;
use App\Models\Tag;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function create(Request $request): Response
    {

        Gate::authorize('create', Member::class);

        $visitId = $request->string('visit')->value();

        $visit = $visitId ? Visit::with('address')->findOrFail($visitId) : null;

        $genders = Gender::options();
        $civilStatuses = CivilStatus::options();
        $skills = Tag::getWithType(TagType::SKILL->value);
        $categories = Tag::getWithType(TagType::CATEGORY->value);

        return Inertia::render('members/create', [
            'genders' => $genders,
            'civilStatuses' => $civilStatuses,
            'skills' => TagResource::collection($skills),
            'categories' => TagResource::collection($categories),
            'visit' => $visit ? new VisitResource($visit) : null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request, CreateMemberAction $action, TransferVisitToMemberAction $transferVisitToMemberAction): RedirectResponse
    {
        Gate::authorize('create', Member::class);
        DB::transaction(function () use ($request, $action, $transferVisitToMemberAction): void {
            /**
             * @var array{
             * name:string,
             * last_name:string,
             * email?:string|null,
             * phone?:string|null,
             * gender:Gender,
             * dob?:string|null,
             * civil_status:CivilStatus,
             * skills:array<int,string>|null|array{},
             * categories:array<int,string>|null|array{},
             * } $validated
             */
            $validated = $request->validated();

            $member = $action->handle([
                'name' => $validated['name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'],
                'dob' => $validated['dob'] ?? null,
                'civil_status' => $validated['civil_status'],
                'skills' => $validated['skills'] ?? [],
                'categories' => $validated['categories'] ?? [],

            ], $request->getAddressData());
            $visitId = $request->string('visit_id')->value();
            if ($visitId) {
                $visit = Visit::findOrFail($visitId);
                $transferVisitToMemberAction->handle($visit, $member);

            }
        });

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

        /**
         * @var array<int, string>|array{} $skills
         */
        $skills = $request->array('skills');
        /**
         * @var array<int, string>|array{} $categories
         */
        $categories = $request->array('categories');

        $action->handle($member, [
            'name' => $request->string('name')->value(),
            'last_name' => $request->string('last_name')->value(),
            'email' => $request->string('email')->value(),
            'phone' => $request->string('phone')->value(),
            'gender' => Gender::from($request->string('gender')->value()),
            'dob' => $request->string('dob')->value() ?: null,
            'civil_status' => CivilStatus::from($request->string('civil_status')->value()),
            'skills' => $skills,
            'categories' => $categories,

        ], $request->getAddressData());

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
