<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Missionary\CreateMissionaryAction;
use App\Actions\Missionary\DeleteMissionaryAction;
use App\Actions\Missionary\ForceDeleteMissionaryAction;
use App\Actions\Missionary\RestoreMissionaryAction;
use App\Actions\Missionary\UpdateMissionaryAction;
use App\Enums\FlashMessageKey;
use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use App\Http\Requests\Missionary\StoreMissionaryRequest;
use App\Http\Requests\Missionary\UpdateMissionaryRequest;
use App\Http\Resources\Missionary\MissionaryResource;
use App\Models\Missionary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class MissionaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Missionary::class);

        $missionaries = Missionary::latest()->get();

        return Inertia::render('missionaries/index', [
            'missionaries' => MissionaryResource::collection($missionaries),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Missionary::class);

        $offeringFrequencies = OfferingFrequency::options();
        $genders = Gender::options();

        return Inertia::render('missionaries/create', [
            'offeringFrequencies' => $offeringFrequencies,
            'genders' => $genders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMissionaryRequest $request, CreateMissionaryAction $action): RedirectResponse
    {
        $action->handle($request->getMissionaryData(), $request->getAddressData());

        return to_route('missionaries.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Missionary')])
        );
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Missionary $missionary)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Missionary $missionary): Response
    {
        Gate::authorize('update', $missionary);

        $missionary->load('address');
        $offeringFrequencies = OfferingFrequency::options();
        $genders = Gender::options();

        return Inertia::render('missionaries/edit', [
            'missionary' => new MissionaryResource($missionary),
            'offeringFrequencies' => $offeringFrequencies,
            'genders' => $genders,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMissionaryRequest $request, Missionary $missionary, UpdateMissionaryAction $action): RedirectResponse
    {
        Gate::authorize('update', $missionary);

        $action->handle(
            $missionary,
            $request->getMissionaryData(),
            $request->getAddressData()
        );

        return to_route('missionaries.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Missionary')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Missionary $missionary, DeleteMissionaryAction $action): RedirectResponse
    {
        Gate::authorize('delete', $missionary);
        $action->handle($missionary);

        return to_route('missionaries.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Missionary')])
        );
    }

    public function restore(Missionary $missionary, RestoreMissionaryAction $action): RedirectResponse
    {
        Gate::authorize('restore', $missionary);

        $action->handle($missionary);

        return to_route('missionaries.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.restored', ['model' => __('Missionary')])
        );
    }

    public function forceDelete(Missionary $missionary, ForceDeleteMissionaryAction $action): RedirectResponse
    {
        Gate::authorize('forceDelete', $missionary);

        $action->handle($missionary);

        return to_route('missionaries.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Missionary')])
        );
    }
}
