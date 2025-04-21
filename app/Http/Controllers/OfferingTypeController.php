<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\StoreOfferingTypeRequest;
use App\Http\Requests\UpdateOfferingTypeRequest;
use App\Http\Resources\Codes\OfferingTypeResource;
use App\Models\OfferingType;
use Inertia\Inertia;

final class OfferingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offeringTypes = OfferingType::latest()->get();

        return Inertia::render('codes/offeringTypes/index', [
            'offeringTypes' => OfferingTypeResource::collection($offeringTypes),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferingTypeRequest $request)
    {
        OfferingType::create($request->validated());

        return to_route('codes.offeringTypes.index')->with(FlashMessageKey::SUCCESS->value, 'Offering type created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferingTypeRequest $request, OfferingType $offeringType)
    {
        $offeringType->update($request->validated());

        return to_route('codes.offeringTypes.index')->with(FlashMessageKey::SUCCESS->value, 'Offering type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferingType $offeringType)
    {
        $offeringType->delete();

        return to_route('codes.offeringTypes.index')->with(FlashMessageKey::SUCCESS->value, 'Offering type deleted successfully.');
    }
}
