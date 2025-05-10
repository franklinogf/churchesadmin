<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Dtos\TransactionDto;
use App\Enums\TransactionType;
use App\Models\ChurchWallet;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Services\FormatterService;

final readonly class UpdateTransactionAction
{
    public function __construct(
        private WalletWithdrawalAction $walletWithdrawalAction,
        private WalletDepositAction $walletDepositAction,
        private FormatterService $formatterService,
    ) {}

    public function handle(Transaction $transaction, TransactionDto $data, TransactionType $transactionType, ?ChurchWallet $wallet = null): Transaction
    {
        $oldWallet = $transaction->wallet;
        $isDeposit = $transactionType === TransactionType::DEPOSIT;

        if (! $wallet instanceof ChurchWallet) {
            $wallet = $oldWallet;
        }

        if ($oldWallet->id !== $wallet->id) {
            $updatedTransaction = $isDeposit
                ? $this->walletDepositAction->handle($wallet, $data)
                : $this->walletWithdrawalAction->handle($wallet, $data);
            $transaction->forceDelete();

        } else {
            $transaction->update([
                'amount' => $this->formatterService->intValue($isDeposit ? $data->amount : -abs((float) $data->amount), 2),
                'meta' => $data->meta->toArray(),
                'confirmed' => $data->confirmed,
            ]);
            $updatedTransaction = $transaction->refresh();

        }

        $oldWallet->refreshBalance();

        return $updatedTransaction;

    }
}
