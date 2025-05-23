<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Enums\EmailStatus;
use App\Enums\FlashMessageKey;
use App\Enums\MediaCollectionName;
use App\Enums\SessionName;
use App\Http\Controllers\Controller;
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

final class EmailController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('communication/emails/index');
    }

    public function create(SessionService $sessionService): Response|RedirectResponse
    {
        /**
         * @var array{type:string,ids:array<int,string>}|null $emailRecipients
         */
        $emailRecipients = $sessionService->get(SessionName::EMAIL_RECIPIENTS);

        if ($emailRecipients === null) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, 'First, select the recipients.');
        }

        $recipientsAmount = count($emailRecipients['ids']);

        if ($recipientsAmount === 0) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, 'You must select at least one recipient.');
        }

        return Inertia::render('communication/emails/create', [
            'recipientsAmount' => $recipientsAmount,
            'recipientsType' => $emailRecipients['type'],
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
        $membersId = $sessionService->get(SessionName::EMAIL_RECIPIENTS, []);

        if (count($membersId) === 0) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, 'You must select at least one recipient.');
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
                ->toMediaCollection(MediaCollectionName::ATTACHMENT->value)
            );

        $email->members()->attach($members, ['status' => EmailStatus::PENDING->value]);

        $sessionService->forget(SessionName::EMAIL_RECIPIENTS);

        dispatch(new SendEmailJob($email));

        return to_route('messages.members.index')->with(FlashMessageKey::SUCCESS->value, 'Messages sent successfully.');

    }
}
