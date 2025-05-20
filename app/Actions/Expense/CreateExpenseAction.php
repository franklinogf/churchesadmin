<?php

declare(strict_types=1);

namespace App\Actions\Expense;

use App\Actions\Wallet\WalletWithdrawalAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Models\Expense;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class CreateExpenseAction
{
    public function __construct(
        private WalletWithdrawalAction $walletWithdrawalAction,
    ) {}

    /**
     * Handle the creation of an expense.
     *
     * @param  array{date:string,wallet_id:string,member_id?:string|null,expense_type_id:string,amount:string,note?:string|null}  $data
     */
    public function handle(array $data): Expense
    {
        $wallet = ChurchWallet::find($data['wallet_id']);

        if ($wallet === null) {
            throw WalletException::notFound();
        }

        try {
            return DB::transaction(function () use ($data, $wallet) {
                $transaction = $this->walletWithdrawalAction->handle($wallet,
                    new TransactionDto(
                        amount: $data['amount'],
                        meta: new TransactionMetaDto(
                            type: TransactionMetaType::EXPENSE
                        )
                    ));

                return Expense::create([
                    'transaction_id' => $transaction->id,
                    'date' => $data['date'],
                    'member_id' => $data['member_id'] ?? null,
                    'expense_type_id' => $data['expense_type_id'],
                    'note' => $data['note'] ?? null,
                ]);
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
