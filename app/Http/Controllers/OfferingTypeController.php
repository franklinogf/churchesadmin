<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\StoreOfferingTypeRequest;
use App\Http\Requests\UpdateOfferingTypeRequest;
use App\Http\Resources\Codes\OfferingTypeResource;
use App\Models\OfferingType;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class OfferingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $offeringTypes = OfferingType::latest()->get();

        return Inertia::render('codes/offeringTypes/index', [
            'offeringTypes' => OfferingTypeResource::collection($offeringTypes),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferingTypeRequest $request): RedirectResponse
    {
        OfferingType::create($request->validated());

        return to_route('codes.offeringTypes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.store', ['model' => __('Code')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferingTypeRequest $request, OfferingType $offeringType): RedirectResponse
    {
        $offeringType->update($request->validated());

        return to_route('codes.offeringTypes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.update', ['model' => __('Code')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferingType $offeringType): RedirectResponse
    {
        $offeringType->delete();

        return to_route('codes.offeringTypes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.delete', ['model' => __('Code')])
        );
    }
}
