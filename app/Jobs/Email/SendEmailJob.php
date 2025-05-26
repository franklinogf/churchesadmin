<?php

declare(strict_types=1);

namespace App\Jobs\Email;

use App\Enums\EmailStatus;
use App\Events\EmailStatusUpdatedEvent;
use App\Models\Email;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SendEmailJob implements ShouldQueue
{
    use Queueable;

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
        foreach ($this->email->pendingMembers as $member) {

            dispatch(new SendCommunicationMessageJob($this->email, $member));

        }

        foreach ($this->email->pendingMissionaries as $missionary) {

            dispatch(new SendCommunicationMessageJob($this->email, $missionary));

        }

        $this->email->update([
            'status' => EmailStatus::SENT,
            'error_message' => null,
            'sent_at' => now(),
        ]);

        event(new EmailStatusUpdatedEvent($this->email));
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
