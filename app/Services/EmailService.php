<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Email\CreateEmailAction;
use App\Enums\ModelMorphName;
use App\Enums\SessionName;
use App\Exceptions\EmailException;
use App\Jobs\Email\SendEmailJob;
use App\Models\Email;
use App\Models\TenantUser;
use App\Services\Session\SessionService;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

final readonly class EmailService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private SessionService $sessionService,
        private CreateEmailAction $createEmailAction,
    ) {
        //
    }

    /**
     * Send an email to the selected recipients.
     *
     * @param  TenantUser  $user  The user sending the email.
     * @param  array{subject:string,body:string}  $data  The email data.
     * @param  array<int,UploadedFile>|null  $files  Optional attachments to include with the email.
     *
     * @throws EmailException
     */
    public function send(TenantUser $user, array $data, ?array $files = null): Email
    {
        try {
            $emailRecipients = $this->getEmailRecipients();

            $email = $this->createEmailAction->handle(
                $user,
                [
                    'subject' => $data['subject'],
                    'body' => $data['body'],
                ],
                $emailRecipients['ids'],
                ModelMorphName::from($emailRecipients['type']),
                $files
            );
            $this->sessionService->forget(SessionName::EMAIL_RECIPIENTS);

            dispatch(new SendEmailJob($email));

            return $email;
        } catch (Exception $e) {
            Log::driver('emails')->error('Failed to send email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'data' => $data,
                'email_recipients' => $emailRecipients ?? null,
            ]);
            if ($e instanceof EmailException) {
                throw $e;
            }

            throw EmailException::unknownError();
        }
    }

    /**
     * Get the recipients for the email from the session.
     *
     * @return array{ids:array<int,string>,type:string}
     *
     * @throws EmailException
     */
    public function getEmailRecipients(): array
    {
        /**
         * @var array{type:string,ids:array<int,string>}|null $emailRecipients
         */
        $emailRecipients = $this->sessionService->get(SessionName::EMAIL_RECIPIENTS);

        if ($emailRecipients === null || count($emailRecipients['ids']) === 0) {
            throw EmailException::noRecipientsSelected();
        }

        $recipientType = ModelMorphName::tryFrom($emailRecipients['type']);

        if ($recipientType === null) {
            throw EmailException::invalidRecipientType();
        }

        return $emailRecipients;
    }
}
