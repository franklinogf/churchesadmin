<?php

declare(strict_types=1);

namespace App\Actions\Expense;

use App\Exceptions\WalletException;
use App\Models\Expense;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class DeleteExpenseAction
{
    /**
     * Handle the deletion of an expense.
     *
     * @return void
     */
    public function handle(Expense $expense): void
    {

        try {

            DB::transaction(function () use ($expense): void {
                $wallet = $expense->transaction->wallet;
                $expense->delete();
                $expense->transaction->forceDelete();
                $wallet->refreshBalance();
            });

        } catch (QueryException $e) {
            Log::error('Error deleting expense: '.$e->getMessage(), [
                'expense_id' => $expense->id,
                'wallet_id' => $expense->transaction->wallet->id,
            ]);

            throw new WalletException('An error occurred while deleting the expense');
        }

    }
}
