<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Exceptions\WalletException;
use App\Models\Check;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CreateCheckAction
{
    /**
     * handle the creation of a check.
     *
     * @param  array{amount:string,member_id:string,date:string,type:string,confirmed:bool}  $data
     * @return Check
     *
     * @throws WalletException
     */
    public function handle(array $data, Wallet $wallet): Check
    {

        try {
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
        } catch (InsufficientFunds) {
            throw WalletException::insufficientFunds($wallet->name);
        } catch (BalanceIsEmpty) {
            throw WalletException::emptyBalance($wallet->name);
        } catch (Exception $e) {
            Log::error('Error creating check: '.$e->getMessage(), [
                'data' => $data,
                'wallet_id' => $wallet->id,
            ]);

            throw new WalletException('An error occurred while creating the check', $e->getCode(), $e);
        }

    }
}
