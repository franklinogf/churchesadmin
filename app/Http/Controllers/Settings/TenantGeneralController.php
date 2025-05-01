<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\UpdateGeneralRequest;
use App\Http\Resources\ChurchResource;
use App\Models\Church;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class TenantGeneralController extends Controller
{
    /**
     * Show the church's language settings page.
     */
    public function edit(): Response
    {
        $church = Church::currentOrFail();

        return Inertia::render('settings/church/general', [
            'church' => new ChurchResource($church),
        ]);
    }

    public function update(UpdateGeneralRequest $request): RedirectResponse
    {

        Church::currentOrFail()->update($request->validated());

        return to_route('church.general.edit');
    }
}
