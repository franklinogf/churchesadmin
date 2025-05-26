<?php

declare(strict_types=1);

namespace App\Jobs\Email;

use App\Enums\EmailStatus;
use App\Mail\CommunicationMessageMail;
use App\Models\Email;
use App\Models\Member;
use App\Models\Missionary;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class SendCommunicationMessageJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Email $email,
        public Member|Missionary $recipient,
    ) {
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->recipient)->send(new CommunicationMessageMail($this->email));

        $this->recipient->emails()->updateExistingPivot($this->email->id, [
            'status' => EmailStatus::SENT,
            'error_message' => null,
            'sent_at' => now(),
        ]);
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
        $this->recipient->emails()->updateExistingPivot($this->email->id, [
            'status' => EmailStatus::FAILED,
            'error_message' => $exception?->getMessage(),
        ]);
    }
}
