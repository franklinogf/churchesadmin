<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Enums\MediaCollectionName;
use App\Http\Controllers\Controller;
use App\Models\Church;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class TenantLogoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,svg|max:2048', // 2MB max
        ]);

        $church = Church::currentOrFail();

        $church->addMediaFromRequest('logo')->toMediaCollection(MediaCollectionName::LOGO->value);

        return to_route('church.general.edit');
    }
}
