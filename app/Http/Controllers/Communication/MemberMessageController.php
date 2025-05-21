<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Enums\FlashMessageKey;
use App\Http\Controllers\Controller;
use App\Http\Resources\Member\MemberResource;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class MemberMessageController extends Controller
{
    public function index(): Response
    {
        $members = Member::all();

        return Inertia::render('communication/members/index', [
            'members' => MemberResource::collection($members),
        ]);
    }

    public function create(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'membersId' => 'required|array',
        ]);

        $membersCount = $request->collect('membersId')->count();

        if ($membersCount === 0) {
            return to_route('messages.members.index')->with(FlashMessageKey::ERROR->value, 'No members selected.');
        }

        return Inertia::render('communication/members/create', [
            'membersCount' => $membersCount,
            'membersId' => $request->array('membersId'),
        ]);
    }
}
