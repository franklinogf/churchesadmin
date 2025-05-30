<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Visit\CreateVisitAction;
use App\Actions\Visit\DeleteVisitAction;
use App\Actions\Visit\ForceDeleteVisitAction;
use App\Actions\Visit\RestoreVisitAction;
use App\Actions\Visit\UpdateVisitAction;
use App\Http\Requests\Visit\StoreVisitRequest;
use App\Http\Requests\Visit\UpdateVisitRequest;
use App\Http\Resources\Visit\VisitResource;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Visit::class);
        $visits = Visit::latest()->get();

        return Inertia::render('main/visits/index', [
            'visits' => VisitResource::collection($visits),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Visit::class);

        return Inertia::render('main/visits/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisitRequest $request, CreateVisitAction $action): RedirectResponse
    {
        $data = $request->getVisitData();
        $address = $request->getAddressData();

        $action->handle($data, $address);

        return to_route('visits.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visit $visit): Response
    {
        Gate::authorize('update', $visit);

        $visit->load(['address']);

        return Inertia::render('main/visits/edit', [
            'visit' => new VisitResource($visit),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitRequest $request, Visit $visit, UpdateVisitAction $action): RedirectResponse
    {
        Gate::authorize('update', $visit);

        $data = $request->getVisitData();
        $address = $request->getAddressData();

        $action->handle($visit, $data, $address);

        return to_route('visits.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit, DeleteVisitAction $action): RedirectResponse
    {
        Gate::authorize('delete', $visit);

        $action->handle($visit);

        return to_route('visits.index');
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(Visit $visit, ForceDeleteVisitAction $action): RedirectResponse
    {
        Gate::authorize('forceDelete', $visit);

        $action->handle($visit);

        return to_route('visits.index');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Visit $visit, RestoreVisitAction $action): RedirectResponse
    {
        Gate::authorize('restore', $visit);

        $action->handle($visit);

        return to_route('visits.index');
    }
}
