<?php

declare(strict_types=1);

namespace App\Actions\Email;

use App\Enums\EmailStatus;
use App\Enums\MediaCollectionName;
use App\Enums\ModelMorphName;
use App\Exceptions\EmailException;
use App\Models\Email;
use App\Models\TenantUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function count;

final class CreateEmailAction
{
    /**
     * Handle the creation of an email.
     *
     * @param  array{subject:string,body:string}  $data
     * @param  array<int,string>  $recipientIds  The IDs of the recipients.
     * @param  ModelMorphName  $recipientType  The type of recipient (Member or Missionary).
     * @param  array<int,UploadedFile>|null  $attachments  Optional attachments to include with the email.
     * @return Email The created email instance.
     *
     * @throws EmailException If an error occurs during email creation.
     */
    public function handle(TenantUser $user, array $data, array $recipientIds, ModelMorphName $recipientType, ?array $attachments = null): Email
    {

        try {
            return DB::transaction(function () use ($user, $data, $recipientIds, $recipientType, $attachments) {

                $email = $user->emails()->create([
                    'subject' => $data['subject'],
                    'body' => $data['body'],
                    'recipients_type' => $recipientType->value,
                    'reply_to' => $user->email,
                    'status' => EmailStatus::PENDING,
                ]);

                if ($attachments !== null) {
                    collect($attachments)
                        ->each(fn (UploadedFile $file) => $email->addMedia($file)
                            ->toMediaCollection(MediaCollectionName::ATTACHMENT->value)
                        );
                }

                match ($recipientType) {
                    ModelMorphName::MEMBER => $this->attachToMembers($email, $recipientIds),
                    ModelMorphName::MISSIONARY => $this->attachToMissionaries($email, $recipientIds),
                    ModelMorphName::VISIT => $this->attachToVisitors($email, $recipientIds),
                    default => throw EmailException::invalidRecipientType(),

                };

                return $email;
            });
        } catch (EmailException $emailException) {
            Log::error('Error creating email', [
                'error' => $emailException->getMessage(),
                'user_id' => $user->id,
                'subject' => $data['subject'],
                'recipients_type' => $recipientType->value,
                'recipients_count' => count($recipientIds),
            ]);
            throw $emailException;
        }
    }

    /**
     * Attach the email to members.
     *
     * @param  array<int,string>  $recipients
     */
    private function attachToMembers(Email $email, array $recipients): void
    {

        $email->members()->attach($recipients, ['status' => EmailStatus::PENDING->value]);
    }

    /**
     * Attach the email to missionaries.
     *
     * @param  array<int,string>  $recipients
     */
    private function attachToMissionaries(Email $email, array $recipients): void
    {
        $email->missionaries()->attach($recipients, ['status' => EmailStatus::PENDING->value]);
    }

    /**
     * Attach the email to visitors.
     *
     * @param  array<int,string>  $recipients
     */
    private function attachToVisitors(Email $email, array $recipients): void
    {
        $email->visits()->attach($recipients, ['status' => EmailStatus::PENDING->value]);
    }
}
