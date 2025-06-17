<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Models\CurrentYear;
use App\Models\TenantUser;
use App\Support\SelectOption;
use DateTimeZone;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request, #[CurrentUser] TenantUser $currentUser): Response
    {
        $country = $request->string('country', $currentUser->timezone_country)->value();

        if ($country === '') {
            $country = $currentUser->timezone_country;
        }

        $workingYears = SelectOption::create(CurrentYear::query()
            ->orderBy('year', 'desc')
            ->get(), labels: 'year');

        $timezones = collect(DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country))
            ->map(fn (string $timezone): array => ['label' => $timezone.' ('.now()->setTimezone($timezone)->format('Y-m-d H:i:s').')', 'value' => $timezone])
            ->sort()
            ->toArray();

        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => true,
            'status' => $request->session()->get('status'),
            'timezones' => $timezones,
            'country' => $country,
            'workingYears' => $workingYears,
        ]);
    }

    /**
     * Update the user's profile settings.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var TenantUser $user */
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->fill(['email_verified_at' => null]);
        }

        $user->save();

        return to_route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        /** @var TenantUser $user */
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');

    }
}
