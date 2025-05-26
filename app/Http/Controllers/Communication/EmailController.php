<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Enums\EmailStatus;
use App\Enums\FlashMessageKey;
use App\Enums\MediaCollectionName;
use App\Enums\ModelMorphName;
use App\Enums\SessionName;
use App\Http\Controllers\Controller;
use App\Http\Resources\Communication\Email\EmailResource;
use App\Jobs\Email\SendEmailJob;
use App\Models\Email;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\TenantUser;
use App\Services\Session\SessionService;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class EmailController extends Controller
{
    public function index(): Response
    {
        $emails = Email::query()
            ->with(['media', 'sender'])
            ->withCount('media')
            ->latest()
            ->get();

        return Inertia::render('communication/emails/index', [
            'emails' => EmailResource::collection($emails),
        ]);
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
         * @var array{type:string,ids:array<int,string>}|null $emailRecipients
         */
        $emailRecipients = $sessionService->get(SessionName::EMAIL_RECIPIENTS);

        if ($emailRecipients === null || count($emailRecipients['ids']) === 0) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, 'You must select at least one recipient.');
        }

        $recipientType = ModelMorphName::tryFrom($emailRecipients['type']);

        if ($recipientType === null) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, 'Invalid recipient type.');
        }

        $email = $user->emails()->create([
            'subject' => $request->string('subject')->value(),
            'body' => $request->string('body')->value(),
            'recipients_type' => $recipientType->value,
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

        match ($recipientType) {
            ModelMorphName::MEMBER => $this->attachToMembers($email, $emailRecipients['ids']),
            ModelMorphName::MISSIONARY => $this->attachToMissionaries($email, $emailRecipients['ids']),
            default => abort(404, 'Invalid recipient type.'),

        };

        $sessionService->forget(SessionName::EMAIL_RECIPIENTS);

        dispatch(new SendEmailJob($email));

        return to_route('communication.emails.index')->with(FlashMessageKey::SUCCESS->value, __('The email will be sent shortly.'));

    }

    /**
     * Attach the email to members.
     *
     * @param  array<int,string>  $recipients
     */
    private function attachToMembers(Email $email, array $recipients): void
    {
        $members = Member::whereIn('id', $recipients)->get();
        $email->members()->attach($members, ['status' => EmailStatus::PENDING->value]);
    }

    /**
     * Attach the email to missionaries.
     *
     * @param  array<int,string>  $recipients
     */
    private function attachToMissionaries(Email $email, array $recipients): void
    {
        $missionaries = Missionary::whereIn('id', $recipients)->get();
        $email->missionaries()->attach($missionaries, ['status' => EmailStatus::PENDING->value]);
    }
}
