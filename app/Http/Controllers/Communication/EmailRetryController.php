<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Enums\EmailStatus;
use App\Enums\FlashMessageKey;
use App\Enums\ModelMorphName;
use App\Events\EmailStatusUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Jobs\Email\SendEmailJob;
use App\Models\Email;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class EmailRetryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Email $email): RedirectResponse
    {

        $recipients = collect();
        if ($email->recipients_type === ModelMorphName::MEMBER) {
            $email->load('members');
            $pendingMembers = $email
                ->members()
                ->where(fn (Builder $query): Builder => $query->where('status', EmailStatus::PENDING)
                    ->orWhere('status', EmailStatus::FAILED))
                ->get();
            $recipients = $recipients->merge($pendingMembers);
        } elseif ($email->recipients_type === ModelMorphName::MISSIONARY) {
            $email->load('missionaries');
            $pendingMissionaries = $email
                ->missionaries()
                ->where(fn (Builder $query): Builder => $query->where('status', EmailStatus::PENDING)
                    ->orWhere('status', EmailStatus::FAILED))
                ->get();
            $recipients = $recipients->merge($pendingMissionaries);
        }

        if ($recipients->isEmpty()) {
            return back()->with(FlashMessageKey::ERROR->value, __('flash.message.email.retry_empty_recipients'));
        }

        $email->update([
            'status' => EmailStatus::PENDING,
        ]);

        event(new EmailStatusUpdatedEvent($email));

        dispatch(new SendEmailJob($email));

        return back()->with(FlashMessageKey::SUCCESS->value, __('flash.message.email.retry_success', ['count' => $recipients->count()]));

    }
}
