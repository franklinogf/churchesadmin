<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Dtos\TransactionDto;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use Bavix\Wallet\Exceptions\AmountInvalid;
use Bavix\Wallet\Internal\Exceptions\TransactionFailedException;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Facades\Log;

final class WalletDepositAction
{
    /**
     * handle the creation of a transaction.
     *
     *
     * @throws WalletException
     */
    public function handle(ChurchWallet $wallet, TransactionDto $transactionDto): Transaction
    {
        try {
            return $wallet->depositFloat(
                $transactionDto->amount,
                $transactionDto->meta->toArray(),
                $transactionDto->confirmed,
            );
        } catch (AmountInvalid) {
            throw WalletException::invalidAmount();
        } catch (RecordsNotFoundException) {
            throw WalletException::notFound();
        } catch (TransactionFailedException) {
            throw WalletException::transactionFailed();
        } catch (QueryException $e) {
            Log::error('Error creating deposit transaction: '.$e->getMessage(), [
                'data' => $transactionDto->toArray(),
                'wallet_id' => $wallet->id,
            ]);

            throw new WalletException('An error occurred while creating the deposit transaction', $e->getCode(), $e);
        }

    }
}
