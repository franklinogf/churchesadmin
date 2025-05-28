<?php

declare(strict_types=1);

namespace App\Jobs\Email;

use App\Enums\EmailStatus;
use App\Events\EmailStatusUpdatedEvent;
use App\Models\Email;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        foreach ($this->email->pendingMembers as $member) {
            $batch[] = new SendCommunicationMessageJob($member->emailMessage);
        }

        foreach ($this->email->pendingMissionaries as $missionary) {
            $batch[] = new SendCommunicationMessageJob($missionary->emailMessage);
        }

        $email = $this->email;

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
