<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\Member\DeactivateMemberRequest;
use App\Models\Member;
use App\Models\Scopes\ActiveMemberScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class MemberStatusController extends Controller
{
    /**
     * Deactivate a member.
     */
    public function deactivate(DeactivateMemberRequest $request, Member $member): RedirectResponse
    {
        Gate::authorize('deactivate', $member);

        $validated = $request->validated();

        $member->update([
            'active' => false,
            'deactivation_code_id' => $validated['deactivation_code_id'],
        ]);

        return redirect()->back()->with(FlashMessageKey::SUCCESS->value, __('Member has been deactivated successfully.'));
    }

    /**
     * Activate a member.
     */
    public function activate(int $memberId): RedirectResponse
    {

        $member = Member::query()
            ->withoutGlobalScope(ActiveMemberScope::class)
            ->findOrFail($memberId);

        Gate::authorize('activate', $member);

        $member->update([
            'active' => true,
            'deactivation_code_id' => null,
        ]);

        return redirect()->back()->with(FlashMessageKey::SUCCESS->value, __('Member has been activated successfully.'));
    }
}
