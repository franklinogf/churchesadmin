<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Member\ActivateMemberAction;
use App\Actions\Member\DeactivateMemberAction;
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
    public function deactivate(DeactivateMemberRequest $request, Member $member, DeactivateMemberAction $action): RedirectResponse
    {
        Gate::authorize('deactivate', $member);
        /**
         * @var array{deactivation_code_id:string} $validated
         */
        $validated = $request->validated();

        $action->handle($member, $validated['deactivation_code_id']);

        return redirect()->back()->with(FlashMessageKey::SUCCESS->value, __('Member has been deactivated successfully.'));
    }

    /**
     * Activate a member.
     */
    public function activate(int $memberId, ActivateMemberAction $action): RedirectResponse
    {

        $member = Member::query()
            ->withoutGlobalScope(ActiveMemberScope::class)
            ->findOrFail($memberId);

        Gate::authorize('activate', $member);

        $action->handle($member);

        return redirect()->back()->with(FlashMessageKey::SUCCESS->value, __('Member has been activated successfully.'));
    }
}
