<?php

declare(strict_types=1);

namespace App\Jobs\Email;

use App\Enums\EmailStatus;
use App\Events\EmailStatusUpdatedEvent;
use App\Exceptions\EmailException;
use App\Models\Email;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SendEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Delete the job if its models no longer exist.
     */
    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(public Email $email)
    {
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $batch = [];

        $email = $this->email;

        $pendingMembers = $email
            ->members()
            ->where(fn(Builder $query): Builder => $query->where('status', EmailStatus::PENDING)
                ->orWhere('status', EmailStatus::FAILED))
            ->get();

        $pendingMissionaries = $email
            ->missionaries()
            ->where(fn(Builder $query): Builder => $query->where('status', EmailStatus::PENDING)
                ->orWhere('status', EmailStatus::FAILED))
            ->get();

        $pendingMembers->each(function ($member) use (&$batch): void {
            $batch[] = new SendCommunicationMessageJob($member->emailMessage);
        });
        $pendingMissionaries->each(function ($missionary) use (&$batch): void {
            $batch[] = new SendCommunicationMessageJob($missionary->emailMessage);
        });

        if ($batch === []) {
            throw EmailException::noRecipientsSelected();
        }
        info('Sending email to '.count($batch).' recipients', [
            'email_id' => $email->id,
            'subject' => $email->subject,
            'members' => $pendingMembers->toArray(),
            'missionaries' => $pendingMissionaries->toArray(),
        ]);

        Bus::batch($batch)
            ->before(function (Batch $batch) use ($email): void {
                $email->update([
                    'status' => EmailStatus::SENDING,
                    'error_message' => null,
                ]);
                event(new EmailStatusUpdatedEvent($email));
            })
            ->finally(function (Batch $batch) use ($email): void {
                $email->update([
                    'status' => EmailStatus::SENT,
                    'error_message' => null,
                    'sent_at' => now(),
                ]);
                event(new EmailStatusUpdatedEvent($email));
            })
            ->name('Send Email: '.$email->subject.' id: '.$email->id)
            ->allowFailures()
            ->onQueue('emails')
            ->dispatch();

    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Log::driver('emails')->error('Failed to send email', [
            'email_id' => $this->email->id,
            'error' => $exception?->getMessage(),
        ]);
        $this->email->update([
            'status' => EmailStatus::FAILED,
            'error_message' => $exception?->getMessage(),
        ]);
        event(new EmailStatusUpdatedEvent($this->email));
    }
}
