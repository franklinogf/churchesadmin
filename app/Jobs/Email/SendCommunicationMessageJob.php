<?php

declare(strict_types=1);

namespace App\Jobs\Email;

use App\Enums\EmailStatus;
use App\Events\EmailableStatusUpdatedEvent;
use App\Mail\CommunicationMessageMail;
use App\Models\Emailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class SendCommunicationMessageJob implements ShouldQueue
{
    use Queueable;

    /**
     * Delete the job if its models no longer exist.
     */
    public bool $deleteWhenMissingModels = true;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * The job will be retried with these delays.
     * The first retry will be after 2 seconds, then 5 seconds, and finally 10 seconds.
     *
     * @var array<int, int>
     */
    public array $backoff = [2, 5, 10];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Emailable $emailable,
    ) {
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->emailable->recipient)->send(new CommunicationMessageMail($this->emailable->email));

        $this->emailable->update([
            'status' => EmailStatus::SENT,
            'error_message' => null,
            'sent_at' => now(),
        ]);

        event(new EmailableStatusUpdatedEvent($this->emailable));

    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Log::driver('emails')->error('Failed to send email', [
            'email_id' => $this->emailable->email_id,
            'error' => $exception?->getMessage(),
        ]);

        $this->emailable->update([
            'status' => EmailStatus::FAILED,
            'error_message' => $exception?->getMessage(),
        ]);

        event(new EmailableStatusUpdatedEvent($this->emailable));

    }
}
