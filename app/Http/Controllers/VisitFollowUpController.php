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
use App\Models\TenantUser;
use App\Models\Visit;
use App\Support\SelectOption;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

        $visit->load(['followUps' => function (HasMany $query): void {
            $query->with('member')->latest();
        }]);

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
    public function store(StoreVisitFollowUpRequest $request, Visit $visit, CreateFollowUpAction $action, #[CurrentUser] TenantUser $currentUser): RedirectResponse
    {
        $action->handle($visit, [
            'member_id' => $request->string('member_id')->value(),
            'type' => FollowUpType::from($request->string('type')->value()),
            'follow_up_at' => $request->string('follow_up_at')->value(),
            'notes' => $request->string('notes')->value() ?: null,
        ]);

        return to_route('visits.follow-ups.index', $visit->id)
            ->with(FlashMessageKey::SUCCESS->value, __('flash.message.created', ['model' => __('Follow Up')]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitFollowUpRequest $request, FollowUp $followUp, UpdateFollowUpAction $action, #[CurrentUser] TenantUser $currentUser): RedirectResponse
    {
        $action->handle($followUp, [
            'member_id' => $request->string('member_id')->value(),
            'type' => FollowUpType::from($request->string('type')->value()),
            'follow_up_at' => $request->string('follow_up_at')->value(),
            'notes' => $request->string('notes')->value() ?: null,
        ]);

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
