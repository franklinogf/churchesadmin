<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\TenantUser;
use App\Models\User;
use Bavix\Wallet\Internal\Events\TransactionCreatedEventInterface;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Auth;

final class TransactionCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCreatedEventInterface $event): void
    {
        /** @var TenantUser|User|null $user */
        $user = Auth::user();

        if ($user === null || ! $user instanceof TenantUser) {
            // If the user is not authenticated or not a TenantUser, skip the listener.
            return;
        }

        $transactionId = $event->getId();
        $transaction = Transaction::query()->withoutGlobalScopes()
            ->where('id', $transactionId)
            ->first();

        $transaction?->update(['meta' => array_merge($transaction->meta ?? [], [
            'year' => $user->current_year_id,
        ])]);

    }
}
