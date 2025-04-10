<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Missionary\CreateMissionaryAction;
use App\Actions\Missionary\DeleteMissionaryAction;
use App\Actions\Missionary\UpdateMissionaryAction;
use App\Enums\FlashMessageKey;
use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use App\Http\Requests\Missionary\CreateMissionaryRequest;
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
        return Inertia::render('missionaries/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMissionaryRequest $request, CreateMissionaryAction $action): RedirectResponse
    {
        $response = Gate::inspect('create', Missionary::class);

        if ($response->denied()) {
            return to_route('missionaries.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $action->handle($request->getMissionaryData(), $request->getAddressData());

        return to_route('missionaries.index')->with(FlashMessageKey::SUCCESS->value, 'Missionary created successfully.');
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
    public function edit(Missionary $missionary)
    {

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
        $response = Gate::inspect('update', $missionary);

        if ($response->denied()) {
            return to_route('missionaries.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }

        $action->handle(
            $missionary,
            $request->getMissionaryData(),
            $request->getAddressData()
        );

        return to_route('missionaries.index')->with(FlashMessageKey::SUCCESS->value, 'Missionary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Missionary $missionary, DeleteMissionaryAction $action): RedirectResponse
    {
        $response = Gate::inspect('delete', $missionary);

        if ($response->denied()) {

            return to_route('missionaries.index')->with(FlashMessageKey::ERROR->value, $response->message());
        }
        $action->handle($missionary);

        return to_route('missionaries.index')->with(FlashMessageKey::SUCCESS->value, 'Missionary deleted successfully.');
    }
}
