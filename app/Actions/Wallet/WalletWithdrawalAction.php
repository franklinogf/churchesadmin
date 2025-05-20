<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Dtos\TransactionDto;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use Bavix\Wallet\Exceptions\AmountInvalid;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Internal\Exceptions\TransactionFailedException;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Facades\Log;

final class WalletWithdrawalAction
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
            return $wallet->withdrawFloat(
                $transactionDto->amount,
                $transactionDto->meta->toArray(),
                $transactionDto->confirmed,
            );
        } catch (AmountInvalid) {
            throw WalletException::invalidAmount();
        } catch (InsufficientFunds) {
            throw WalletException::insufficientFunds($wallet->name);
        } catch (BalanceIsEmpty) {
            throw WalletException::emptyBalance($wallet->name);
        } catch (RecordsNotFoundException) {
            throw WalletException::notFound();
        } catch (TransactionFailedException) {
            throw WalletException::transactionFailed();
        } catch (QueryException $e) {
            Log::error('Error creating withdrawal transaction: '.$e->getMessage(), [
                'data' => $transactionDto->toArray(),
                'wallet_id' => $wallet->id,
            ]);

            throw new WalletException('An error occurred while creating the withdrawal transaction', $e->getCode(), $e);
        }

    }
}
