<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Enums\FlashMessageKey;
use App\Exceptions\EmailException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Email\StoreEmailRequest;
use App\Http\Resources\Communication\Email\EmailResource;
use App\Models\Email;
use App\Models\TenantUser;
use App\Services\EmailService;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

use function count;

final class EmailController extends Controller
{
    public function index(): Response
    {

        Gate::authorize('viewAny', Email::class);

        $emails = Email::query()
            ->with(['media', 'sender'])
            ->withCount('media')
            ->latest()
            ->get();

        return Inertia::render('communication/emails/index', [
            'emails' => EmailResource::collection($emails),
        ]);
    }

    public function show(Email $email): Response
    {
        Gate::authorize('viewAny', Email::class);
        $email->load(['media', 'sender']);

        return Inertia::render('communication/emails/show', [
            'email' => new EmailResource($email),
        ]);
    }

    public function create(EmailService $emailService): Response|RedirectResponse
    {
        Gate::authorize('create', Email::class);
        try {
            $emailRecipients = $emailService->getEmailRecipients();
        } catch (EmailException $emailException) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, $emailException->getMessage());
        }

        $recipientsAmount = count($emailRecipients['ids']);

        return Inertia::render('communication/emails/create', [
            'recipientsAmount' => $recipientsAmount,
            'recipientsType' => $emailRecipients['type'],
        ]);
    }

    public function store(StoreEmailRequest $request, #[CurrentUser] TenantUser $user, EmailService $emailService): RedirectResponse
    {

        /**
         * @var array<int,UploadedFile>|null $files
         */
        $files = $request->file('media');
        try {
            $email = $emailService->send(
                $user,
                [
                    'subject' => $request->string('subject')->value(),
                    'body' => $request->string('body')->value(),
                ],
                $files
            );

        } catch (EmailException $emailException) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, $emailException->getMessage());
        }

        return to_route('communication.emails.show', $email)->with(FlashMessageKey::SUCCESS->value, __('flash.message.email.will_be_sent'));

    }
}
