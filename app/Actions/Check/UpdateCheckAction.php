<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Models\Check;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

final class UpdateCheckAction
{
    public function handle(Check $check, array $data, Wallet $wallet): Check
    {
        DB::transaction(function () use ($check, $data, $wallet): void {
            $oldTransaction = $check->transaction;

            if ($oldTransaction->wallet !== $wallet) {
                $oldTransaction->forceDelete();
                $oldTransaction->wallet->refreshBalance();
                $newTransaction = $wallet->withdrawFloat($data['amount'], confirmed: $data['confirmed']);
                $check->update(['transaction_id' => $newTransaction?->id]);

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
