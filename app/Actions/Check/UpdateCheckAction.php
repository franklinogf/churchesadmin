<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Actions\Wallet\UpdateTransactionAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Enums\TransactionType;
use App\Exceptions\WalletException;
use App\Models\Check;
use App\Models\Church;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class UpdateCheckAction
{
    public function __construct(
        private UpdateTransactionAction $updateTransactionAction,
    ) {}

    /**
     * handle the update of a check.
     *
     * @param  array{amount?:string,member_id?:string,date?:string,type?:string,confirmed?:bool,wallet_slug?:string,note?:string|null,expense_type_id?:string,check_number?:string}  $data
     * @return Check
     *
     * @throws WalletException
     */
    public function handle(Check $check, array $data): Check
    {
        $wallet = $data['wallet_slug'] ? Church::current()?->getWallet($data['wallet_slug']) : $check->transaction->wallet;

        if (! $wallet instanceof Wallet) {
            throw WalletException::notFound();
        }

        try {
            DB::transaction(function () use ($check, $data, $wallet): void {

                $transaction = $this->updateTransactionAction
                    ->handle($check->transaction, new TransactionDto(
                        amount: $data['amount'] ?? $check->transaction->amountFloat,
                        meta: new TransactionMetaDto(
                            type: TransactionMetaType::CHECK,
                        ),
                        confirmed: $data['confirmed'] ?? $check->transaction->confirmed,
                    ),
                        TransactionType::WITHDRAW,
                        $wallet);

                $check->update([
                    'transaction_id' => $transaction->id,
                    'member_id' => $data['member_id'] ?? $check->member_id,
                    'date' => $data['date'] ?? $check->date,
                    'type' => $data['type'] ?? $check->type,
                    'expense_type_id' => $data['expense_type_id'] ?? $check->expense_type_id,
                    'check_number' => $data['check_number'] ?? $check->check_number,
                    'note' => $data['note'] ?? $check->note,
                ]);
            });

            return $check->refresh();
        } catch (QueryException $e) {
            Log::error('Error updating check: '.$e->getMessage(), [
                'check_id' => $check->id,
                'data' => $data,
                'wallet_id' => $wallet?->id,
            ]);

            throw new WalletException('An error occurred while updating the check', $e->getCode(), $e);
        }

    }
}
