<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Enums\MediaCollectionName;
use App\Enums\TenantPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\UpdateGeneralRequest;
use App\Http\Resources\ChurchResource;
use App\Models\Church;
use App\Models\TenantUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class TenantGeneralController extends Controller
{
    /**
     * Show the church's language settings page.
     */
    public function edit(#[CurrentUser] TenantUser $user): Response
    {

        if ($user->cannot(TenantPermission::SETTINGS_MANAGE)) {
            abort(403);
        }

        $church = Church::currentOrFail();

        return Inertia::render('settings/church/general', [
            'church' => new ChurchResource($church),
        ]);
    }

    public function update(UpdateGeneralRequest $request): RedirectResponse
    {

        $church = Church::currentOrFail();
        $church->update($request->validated());

        if ($request->hasFile('logo')) {
            $church->addMediaFromRequest('logo')->toMediaCollection(MediaCollectionName::LOGO->value);
        }

        return to_route('church.general.edit');
    }
}
