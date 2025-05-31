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
use App\Models\ChurchWallet;
use App\Support\ArrayFallback;
use Illuminate\Support\Facades\DB;

final readonly class UpdateCheckAction
{
    public function __construct(
        private UpdateTransactionAction $updateTransactionAction,
    ) {}

    /**
     * handle the update of a check.
     *
     * @param  array{amount?:string,member_id?:string,date?:string,type?:string,confirmed?:bool,wallet_id?:string,note?:string|null,expense_type_id?:string,check_number?:string|null}  $data
     *
     * @throws WalletException
     */
    public function handle(Check $check, array $data): Check
    {
        $wallet = ChurchWallet::find($data['wallet_id'] ?? $check->transaction->wallet->holder_id);

        if (! $wallet instanceof ChurchWallet) {
            throw WalletException::notFound();
        }

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
                'check_number' => ArrayFallback::inputOrFallback($data, 'check_number', $check->check_number),
                'note' => ArrayFallback::inputOrFallback($data, 'note', $check->note),
            ]);
        });

        return $check->refresh();

    }
}
