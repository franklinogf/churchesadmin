<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Enums\LanguageCode;
use App\Http\Requests\Code\StoreOfferingTypeRequest;
use App\Http\Requests\Code\UpdateOfferingTypeRequest;
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
        /**
         * @var array{name:string} $validated
         */
        $validated = $request->validated();

        OfferingType::create(['name' => collect(LanguageCode::values())
            ->mapWithKeys(fn (string $code) => [$code => $validated['name']])
            ->toArray()]);

        return to_route('codes.offeringTypes.index')->with(FlashMessageKey::SUCCESS->value, 'Offering type created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferingTypeRequest $request, OfferingType $offeringType): RedirectResponse
    {
        /**
         * @var array{name:string} $validated
         */
        $validated = $request->validated();
        $offeringType->update(['name' => collect(LanguageCode::values())
            ->mapWithKeys(fn (string $code) => [$code => $validated['name']])
            ->toArray()]);

        return to_route('codes.offeringTypes.index')->with(FlashMessageKey::SUCCESS->value, 'Offering type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferingType $offeringType): RedirectResponse
    {
        $offeringType->delete();

        return to_route('codes.offeringTypes.index')->with(FlashMessageKey::SUCCESS->value, 'Offering type deleted successfully.');
    }
}
