<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Dtos\TransactionDto;
use App\Enums\TransactionType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use Bavix\Wallet\Internal\Exceptions\RecordNotFoundException;
use Bavix\Wallet\Internal\Exceptions\TransactionFailedException;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Services\FormatterService;
use Illuminate\Support\Facades\DB;

final readonly class UpdateTransactionAction
{
    public function __construct(
        private WalletWithdrawalAction $walletWithdrawalAction,
        private WalletDepositAction $walletDepositAction,
        private FormatterService $formatterService,
    ) {}

    public function handle(Transaction $transaction, TransactionDto $transactionDto, TransactionType $transactionType, ?ChurchWallet $wallet = null): Transaction
    {
        $oldWallet = ChurchWallet::find($transaction->wallet->holder_id);
        $isDeposit = $transactionType === TransactionType::DEPOSIT;

        if (! $wallet instanceof ChurchWallet) {
            $wallet = $oldWallet;
        }

        if (! $oldWallet instanceof ChurchWallet || ! $wallet instanceof ChurchWallet) {
            throw WalletException::notFound();
        }

        try {
            return DB::transaction(function () use ($transaction, $transactionDto, $isDeposit, $wallet, $oldWallet): Transaction {
                if ($oldWallet->id !== $wallet->id) {
                    $transaction->delete();

                    $updatedTransaction = $isDeposit
                            ? $this->walletDepositAction->handle($wallet, $transactionDto)
                            : $this->walletWithdrawalAction->handle($wallet, $transactionDto);

                } else {
                    $amount = $this->formatterService->intValue($isDeposit ? $transactionDto->amount : -abs((float) $transactionDto->amount), 2);
                    if ($amount !== $transaction->amount) {
                        $transaction->delete();
                        $updatedTransaction = $isDeposit
                            ? $this->walletDepositAction->handle($wallet, $transactionDto)
                            : $this->walletWithdrawalAction->handle($wallet, $transactionDto);
                    } else {
                        $updatedTransaction = $transaction;
                    }

                }

                return $updatedTransaction;
            });
        } catch (RecordNotFoundException) {
            throw WalletException::notFound();
        } catch (TransactionFailedException) {
            throw WalletException::transactionFailed();
        }

    }
}
