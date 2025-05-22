<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\CommunicationMessageMail;
use App\Models\Email;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

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
        foreach ($this->email->members as $member) {
            Mail::to($member)->send(new CommunicationMessageMail($this->email));
        }

        foreach ($this->email->missionaries as $missionary) {
            Mail::to($missionary)->send(new CommunicationMessageMail($this->email));
        }
    }
}
