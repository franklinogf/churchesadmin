<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Enums\LanguageCode;
use App\Enums\TenantPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\UpdateLanguageRequest;
use App\Models\Church;
use App\Models\TenantUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class TenantLanguageController extends Controller
{
    /**
     * Show the church's language settings page.
     */
    public function edit(#[CurrentUser] TenantUser $user): Response
    {
        if ($user->cannot(TenantPermission::SETTINGS_CHANGE_LANGUAGE)) {
            abort(403);
        }

        $languages = LanguageCode::options();

        return Inertia::render('settings/church/language', [
            'languages' => $languages,
        ]);
    }

    public function update(UpdateLanguageRequest $request, #[CurrentUser] TenantUser $user): RedirectResponse
    {

        /**
         * @var array{locale:string} $validated
         */
        $validated = $request->validated();

        Church::current()?->update([
            'locale' => $validated['locale'],
        ]);

        return to_route('church.language.edit');
    }
}
