<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Actions\Wallet\WalletWithdrawalAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\Check;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CreateCheckAction
{
    public function __construct(
        private readonly WalletWithdrawalAction $walletWithdrawalAction,
    ) {}

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

                $transaction = $this->walletWithdrawalAction->handle($wallet, new TransactionDto(
                    amount: $data['amount'],
                    meta: new TransactionMetaDto(
                        type: TransactionMetaType::CHECK,
                    ),
                    confirmed: $data['confirmed'] ?? true,
                ));

                return Check::create([
                    'transaction_id' => $transaction->id,
                    'member_id' => $data['member_id'],
                    'date' => $data['date'],
                    'type' => $data['type'],
                ]);
            });
        } catch (QueryException $e) {
            Log::error('Error creating check: '.$e->getMessage(), [
                'data' => $data,
                'wallet_id' => $wallet->id,
            ]);

            throw new WalletException('An error occurred while creating the check', $e->getCode(), $e);
        }

    }
}
