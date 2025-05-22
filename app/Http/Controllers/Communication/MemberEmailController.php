<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Enums\EmailStatus;
use App\Enums\FlashMessageKey;
use App\Enums\MediaCollectionName;
use App\Enums\SessionName;
use App\Http\Controllers\Controller;
use App\Http\Resources\Member\MemberResource;
use App\Jobs\Email\SendEmailJob;
use App\Models\Member;
use App\Models\TenantUser;
use App\Services\Session\SessionService;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class MemberEmailController extends Controller
{
    public function index(): Response
    {
        $members = Member::all();

        return Inertia::render('communication/members/index', [
            'members' => MemberResource::collection($members),
        ]);
    }

    public function create(SessionService $sessionService): Response|RedirectResponse
    {
        /**
         * @var array<int, string> $membersId
         */
        $membersId = $sessionService->get(SessionName::EMAIL_MEMBERS_IDS, []);

        $membersAmount = count($membersId);

        if ($membersAmount === 0) {
            return to_route('messages.members.index')->with(FlashMessageKey::ERROR->value, 'No members selected.');
        }

        return Inertia::render('communication/members/create', [
            'membersAmount' => $membersAmount,
        ]);
    }

    public function store(Request $request, #[CurrentUser] TenantUser $user, SessionService $sessionService): RedirectResponse
    {
        $request->validate([
            'body' => 'required|string',
            'subject' => 'required|string|max:255',
            'media.*' => 'file|max:10240', // 10MB
        ]);
        /**
         * @var array<int, string> $membersId
         */
        $membersId = $sessionService->get(SessionName::EMAIL_MEMBERS_IDS, []);

        if (count($membersId) === 0) {
            return to_route('messages.members.index')->with(FlashMessageKey::ERROR->value, 'No members selected.');
        }

        $body = $request->string('body')->value();
        $subject = $request->string('subject')->value();

        $members = Member::whereIn('id', $membersId)->get();

        $email = $user->emails()->create([
            'subject' => $subject,
            'body' => $body,
            'recipient_type' => Relation::getMorphAlias(Member::class),
            'reply_to' => $user->email,
            'status' => EmailStatus::PENDING,
        ]);
        /**
         * @var array<int, UploadedFile> $files
         */
        $files = $request->file('media', []);
        collect($files)
            ->each(fn (UploadedFile $file) => $email->addMedia($file)
                ->toMediaCollection(MediaCollectionName::ATTATCHMENT->value)
            );

        $email->members()->attach($members, ['status' => EmailStatus::PENDING->value]);

        $sessionService->forget(SessionName::EMAIL_MEMBERS_IDS);

        dispatch(new SendEmailJob($email));

        return to_route('messages.members.index')->with(FlashMessageKey::SUCCESS->value, 'Messages sent successfully.');

    }
}
