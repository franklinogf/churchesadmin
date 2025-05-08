<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Models\Check;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Support\Facades\DB;

final class CreateCheckAction
{
    /**
     * handle the creation of a check.
     *
     * @param  array{amount:string,member_id:string,date:string,type:string,confirmed:bool}  $data
     * @return \App\Models\Check
     */
    public function handle(array $data, Wallet $wallet): Check
    {
        return DB::transaction(function () use ($data, $wallet): Check {

            $transaction = $wallet->withdrawFloat(
                $data['amount'],
                confirmed: $data['confirmed'],
            );

            return Check::create([
                'transaction_id' => $transaction->id,
                'member_id' => $data['member_id'],
                'date' => $data['date'],
                'type' => $data['type'],
            ]);
        });
    }
}
