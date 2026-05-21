<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\CloseYearAction;
use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Http\Controllers\Controller;
use App\Models\CurrentYear;
use App\Models\TenantUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class TenantYearEndController extends Controller
{
    public function edit(#[CurrentUser] TenantUser $user): Response
    {
        abort_if($user->cannot(TenantPermission::SETTINGS_CLOSE_YEAR), 403);

        $currentYear = CurrentYear::current();

        return Inertia::render('settings/church/year-end', [
            'currentYear' => (int) $currentYear->year,

        ]);
    }

    public function update(CloseYearAction $action, #[CurrentUser] TenantUser $user): RedirectResponse
    {
        abort_if($user->cannot(TenantPermission::SETTINGS_CLOSE_YEAR), 403);

        $action->handle();

        return to_route('church.general.year-end.edit')
            ->with(FlashMessageKey::SUCCESS->value, __('Fiscal year closed successfully.'));
    }
}
