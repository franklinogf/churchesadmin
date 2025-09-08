<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\Code\StoreOfferingTypeRequest;
use App\Http\Requests\Code\UpdateOfferingTypeRequest;
use App\Http\Resources\Codes\OfferingTypeResource;
use App\Models\OfferingType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class OfferingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', OfferingType::class);

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
        Gate::authorize('create', OfferingType::class);

        OfferingType::create($request->validated());

        return to_route('codes.offeringTypes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Offering Type')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferingTypeRequest $request, OfferingType $offeringType): RedirectResponse
    {
        Gate::authorize('update', $offeringType);

        $offeringType->update($request->validated());

        return to_route('codes.offeringTypes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Offering Type')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferingType $offeringType): RedirectResponse
    {
        Gate::authorize('delete', $offeringType);

        $offeringType->delete();

        return to_route('codes.offeringTypes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Offering Type')])
        );
    }
}
