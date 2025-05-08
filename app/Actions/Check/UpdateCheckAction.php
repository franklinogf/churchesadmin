<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Models\Check;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Support\Facades\DB;

final class UpdateCheckAction
{
    /**
     * handle the update of a check.
     *
     * @param  array{amount?:string,member_id?:string,date?:string,type?:string,confirmed?:bool}  $data
     * @return Check
     */
    public function handle(Check $check, array $data, ?Wallet $wallet = null): Check
    {
        DB::transaction(function () use ($check, $data, $wallet): void {
            $oldTransaction = $check->transaction;

            if (! $wallet instanceof Wallet) {
                $wallet = $oldTransaction->wallet;
            }

            if ($oldTransaction->wallet !== $wallet) {
                $oldTransaction->forceDelete();
                $oldTransaction->wallet->refreshBalance();
                $newTransaction = $wallet->withdrawFloat($data['amount'] ?? $oldTransaction->amount, confirmed: $data['confirmed'] ?? $oldTransaction->confirmed);
                $check->update(['transaction_id' => $newTransaction->id]);

            } else {
                $oldTransaction->update([
                    'amount' => $data['amount'] ?? $oldTransaction->amount,
                    'confirmed' => $data['confirmed'] ?? $oldTransaction->confirmed,
                ]);
                $oldTransaction->wallet->refreshBalance();
            }

            $check->update([
                'member_id' => $data['member_id'] ?? $check->member_id,
                'date' => $data['date'] ?? $check->date,
                'type' => $data['type'] ?? $check->type,
            ]);
        });

        return $check->refresh();
    }
}
