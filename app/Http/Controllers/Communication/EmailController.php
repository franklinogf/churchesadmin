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

    public function create(EmailService $emailService): Response|RedirectResponse
    {
        Gate::authorize('create', Email::class);
        try {
            $emailRecipients = $emailService->getEmailRecipients();
        } catch (EmailException $e) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, $e->getMessage());
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
            $emailService->send(
                $user,
                [
                    'subject' => $request->string('subject')->value(),
                    'body' => $request->string('body')->value(),
                ],
                $files
            );

        } catch (EmailException $e) {
            return to_route('communication.emails.index')->with(FlashMessageKey::ERROR->value, $e->getMessage());
        }

        return to_route('communication.emails.index')->with(FlashMessageKey::SUCCESS->value, __('flash.message.email.will_be_sent'));

    }
}
