<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Models\Check;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

final class CreateCheckAction
{
    public function handle(array $data, Wallet $wallet): Check
    {
        return DB::transaction(function () use ($data, $wallet): Check {

            $transaction = $wallet?->withdrawFloat(
                $data['amount'],
                confirmed: $data['confirmed'],
            );

            return Check::create([
                'transaction_id' => $transaction?->id,
                'member_id' => $data['member_id'],
                'date' => $data['date'],
                'type' => $data['type'],
            ]);
        });
    }
}
