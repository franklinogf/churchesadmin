<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FollowUpType;
use App\Http\Requests\Visit\StoreVisitRequest;
use App\Http\Requests\Visit\UpdateVisitRequest;
use App\Http\Resources\Visit\VisitResource;
use App\Models\Visit;
use Inertia\Inertia;
use Inertia\Response;

final class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $visits = Visit::latest()->get();

        return Inertia::render('main/visits/index', [
            'visits' => VisitResource::collection($visits),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('main/visits/create', [
            'followUpTypes' => FollowUpType::options(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisitRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Visit $visit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visit $visit): Response
    {
        return Inertia::render('main/visits/edit', [
            'visit' => $visit,
            'followUpTypes' => FollowUpType::options(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitRequest $request, Visit $visit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
    {
        //
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(Visit $visit)
    {
        //
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Visit $visit)
    {
        //
    }
}
