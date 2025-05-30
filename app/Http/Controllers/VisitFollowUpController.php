<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Visit\CreateFollowUpAction;
use App\Actions\Visit\DeleteFollowUpAction;
use App\Actions\Visit\UpdateFollowUpAction;
use App\Enums\FlashMessageKey;
use App\Enums\FollowUpType;
use App\Http\Requests\Visit\StoreVisitFollowUpRequest;
use App\Http\Requests\Visit\UpdateVisitFollowUpRequest;
use App\Http\Resources\Visit\VisitResource;
use App\Models\FollowUp;
use App\Models\Member;
use App\Models\Visit;
use App\Support\SelectOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class VisitFollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Visit $visit): Response
    {
        Gate::authorize('viewAny', $visit);

        $visit->load('followUps.member');
        $memberOptions = SelectOption::create(Member::all(), labels: ['name', 'last_name']);
        $followUpTypeOptions = FollowUpType::options();

        return Inertia::render('main/visits/follow-ups/index', [
            'visit' => new VisitResource($visit),
            'memberOptions' => $memberOptions,
            'followUpTypeOptions' => $followUpTypeOptions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisitFollowUpRequest $request, Visit $visit, CreateFollowUpAction $action): RedirectResponse
    {
        /**
         * @var array{
         *     member_id: string,
         *     type: FollowUpType,
         *     follow_up_at: string,
         *     notes: string|null
         * } $data
         */
        $data = $request->validated();
        $action->handle($visit, $data);

        return to_route('visits.follow-ups.index', $visit->id)
            ->with(FlashMessageKey::SUCCESS->value, __('flash.message.created', ['model' => __('Follow Up')]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitFollowUpRequest $request, FollowUp $followUp, UpdateFollowUpAction $action): RedirectResponse
    {
        /**
         * @var array{
         *     member_id: string,
         *     type: FollowUpType,
         *     follow_up_at: string,
         *     notes: string|null
         * } $data
         */
        $data = $request->validated();
        $action->handle($followUp, $data);

        return to_route('visits.follow-ups.index', $followUp->visit_id)
            ->with(FlashMessageKey::SUCCESS->value, __('flash.message.updated', ['model' => __('Follow Up')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FollowUp $followUp, DeleteFollowUpAction $action): RedirectResponse
    {

        $action->handle($followUp);

        return to_route('visits.follow-ups.index', $followUp->visit_id)
            ->with(FlashMessageKey::SUCCESS->value, __('flash.message.deleted', ['model' => __('Follow Up')]));
    }
}
