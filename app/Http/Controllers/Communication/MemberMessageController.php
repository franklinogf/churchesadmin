<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Enums\FlashMessageKey;
use App\Http\Controllers\Controller;
use App\Http\Resources\Member\MemberResource;
use App\Mail\CommunicationMessage;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail;
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

        $membersAmount = $request->collect('membersId')->count();

        if ($membersAmount === 0) {
            return to_route('messages.members.index')->with(FlashMessageKey::ERROR->value, 'No members selected.');
        }

        return Inertia::render('communication/members/create', [
            'membersAmount' => $membersAmount,
            'membersId' => $request->array('membersId'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'membersId' => 'required|array',
            'membersId.*' => 'exists:members,id',
            'body' => 'required|string',
            'subject' => 'required|string|max:255',
        ]);

        $body = $request->string('body')->value();
        $subject = $request->string('subject')->value();

        $members = Member::whereIn('id', $request->array('membersId'))->get();

        $members->each(fn (Member $member): ?SentMessage => Mail::to($member)
            ->send(
                new CommunicationMessage($body)
                    ->subject($subject)
            ));

        return to_route('messages.members.index')->with(FlashMessageKey::SUCCESS->value, 'Messages sent successfully.');

    }
}
