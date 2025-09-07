<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Actions\Wallet\WalletWithdrawalAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\Check;
use App\Models\ChurchWallet;
use Illuminate\Support\Facades\DB;

final readonly class CreateCheckAction
{
    public function __construct(
        private WalletWithdrawalAction $walletWithdrawalAction,
    ) {}

    /**
     * handle the creation of a check.
     *
     * @param  array{amount:string,member_id:string,date:string,type:string,wallet_id:string,note?:string|null,expense_type_id:string,check_number?:string}  $data
     *
     * @throws WalletException
     */
    public function handle(array $data): Check
    {
        $wallet = ChurchWallet::find($data['wallet_id']);

        if (! $wallet instanceof ChurchWallet) {
            throw WalletException::notFound();
        }

        return DB::transaction(function () use ($data, $wallet): Check {

            $transaction = $this->walletWithdrawalAction->handle($wallet, new TransactionDto(
                amount: $data['amount'],
                meta: new TransactionMetaDto(
                    type: TransactionMetaType::CHECK,
                ),
                confirmed: false,
            ));

            return Check::create([
                'transaction_id' => $transaction->id,
                'member_id' => $data['member_id'],
                'date' => $data['date'],
                'type' => $data['type'],
                'expense_type_id' => $data['expense_type_id'],
                'check_number' => $data['check_number'] ?? null,
                'note' => $data['note'] ?? null,
            ]);
        });

    }
}
