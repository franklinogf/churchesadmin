<?php

declare(strict_types=1);

namespace App\Actions\Expense;

use App\Actions\Wallet\UpdateTransactionAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Enums\TransactionType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Models\Expense;
use App\Support\ArrayFallback;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class UpdateExpenseAction
{
    public function __construct(
        private UpdateTransactionAction $updateTransactionAction,
    ) {}

    /**
     * Handle the creation of an expense.
     *
     * @param  array{date?:string,wallet_id?:string,member_id?:string|null,expense_type_id?:string,amount?:string,note?:string|null}  $data
     */
    public function handle(Expense $expense, array $data): Expense
    {
        $wallet = ChurchWallet::find($data['wallet_id'] ?? $expense->transaction->wallet->holder_id);

        if ($wallet === null) {
            throw WalletException::notFound();
        }

        try {
            return DB::transaction(function () use ($data, $expense) {

                $transaction = $this->updateTransactionAction->handle(
                    $expense->transaction,
                    new TransactionDto(
                        amount: $data['amount'] ?? $expense->transaction->amount,
                        meta: new TransactionMetaDto(
                            type: TransactionMetaType::EXPENSE,
                        )
                    ),
                    TransactionType::WITHDRAW
                );

                $expense->update([
                    'transaction_id' => $transaction->id,
                    'date' => $data['date'] ?? $expense->date,
                    'note' => ArrayFallback::inputOrFallback($data, 'note', $expense->note),
                    'member_id' => ArrayFallback::inputOrFallback($data, 'member_id', $expense->member_id),

                ]);

                return $expense->refresh();
            });
        } catch (QueryException $e) {
            Log::error('Error creating expense transaction: '.$e->getMessage(), [
                'data' => $data,
                'wallet_id' => $wallet->id,
            ]);

            throw new WalletException('An error occurred while creating the expense', $e->getCode(), $e);
        }
    }
}
