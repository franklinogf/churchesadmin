<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Dtos\TransactionDto;
use App\Exceptions\WalletException;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Log;

final class WalletWithdrawalAction
{
    /**
     * handle the creation of a transaction.
     *
     * @param  array{amount:string,confirmed?:bool}  $data
     * @return Transaction
     *
     * @throws WalletException
     */
    public function handle(Wallet $wallet, TransactionDto $transactionDto): Transaction
    {
        try {
            return $wallet->withdrawFloat(
                $transactionDto->amount,
                $transactionDto->meta->toArray(),
                $transactionDto->confirmed,
            );
        } catch (InsufficientFunds) {
            throw WalletException::insufficientFunds($wallet->name);
        } catch (BalanceIsEmpty) {
            throw WalletException::emptyBalance($wallet->name);
        } catch (Exception $e) {
            Log::error('Error creating withdrawal transaction: '.$e->getMessage(), [
                'data' => $transactionDto->toArray(),
                'wallet_id' => $wallet->id,
            ]);

            throw new WalletException('An error occurred while creating the withdrawal transaction', $e->getCode(), $e);
        }

    }
}
