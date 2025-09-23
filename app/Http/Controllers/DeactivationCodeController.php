<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\Code\StoreDeactivationCodeRequest;
use App\Http\Requests\Code\UpdateDeactivationCodeRequest;
use App\Http\Resources\Codes\DeactivationCodeResource;
use App\Models\DeactivationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class DeactivationCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', DeactivationCode::class);

        $deactivationCodes = DeactivationCode::latest()->get();

        return Inertia::render('codes/deactivationCodes/index', [
            'deactivationCodes' => DeactivationCodeResource::collection($deactivationCodes),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeactivationCodeRequest $request): RedirectResponse
    {
        Gate::authorize('create', DeactivationCode::class);

        DeactivationCode::create($request->validated());

        return to_route('codes.deactivationCodes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Deactivation Code')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeactivationCodeRequest $request, DeactivationCode $deactivationCode): RedirectResponse
    {
        Gate::authorize('update', $deactivationCode);

        $deactivationCode->update($request->validated());

        return to_route('codes.deactivationCodes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Deactivation Code')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeactivationCode $deactivationCode): RedirectResponse
    {
        Gate::authorize('delete', $deactivationCode);

        $deactivationCode->delete();

        return to_route('codes.deactivationCodes.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Deactivation Code')])
        );
    }
}
