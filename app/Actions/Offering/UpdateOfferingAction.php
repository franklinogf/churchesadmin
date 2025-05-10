<?php

declare(strict_types=1);

namespace App\Actions\Offering;

use App\Actions\Wallet\UpdateTransactionAction;
use App\Actions\Wallet\WalletDepositAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Enums\TransactionType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Models\Offering;
use App\Support\ArrayFallback;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class UpdateOfferingAction
{
    public function __construct(
        private UpdateTransactionAction $updateTransactionAction,
    ) {}

    /**
     * Handle the creation of an offering.
     *
     * @param  array{wallet_id?:string, date?:string, donor_id?:string|null, amount?:string, payment_method?:string, offering_type?:array{id:string, model:string}}  $data
     * @return Offering
     */
    public function handle(Offering $offering, array $data): Offering
    {
        $wallet = ChurchWallet::find($data['wallet_id']);

        if ($wallet === null) {
            throw WalletException::notFound();
        }

        try {
            return DB::transaction(function () use ($data, $offering) {

                $transaction = $this->updateTransactionAction->handle(
                    $offering->transaction,
                    new TransactionDto(
                        amount: $data['amount'] ?? $offering->transaction->amount,
                        meta: new TransactionMetaDto(
                            type: TransactionMetaType::OFFERING,
                        )
                    ),
                    TransactionType::DEPOSIT
                );

                $offering->update([
                    'transaction_id' => $transaction->id,
                    'date' => $data['date'] ?? $offering->date,
                    'payment_method' => $data['payment_method'] ?? $offering->payment_method,
                    'note' => $data['note'] ?? $offering->note,
                    'donor_id' => ArrayFallback::inputOrFallback($data, 'donor_id', $offering->donor_id),
                    'offering_type_id' => isset($data['offering_type']) ? $data['offering_type']['id'] : $offering->offering_type_id,
                    'offering_type_type' => isset($data['offering_type']) ? $data['offering_type']['model'] : $offering->offering_type_type,
                ]);

                return $offering->refresh();
            });
        } catch (QueryException $e) {
            Log::error('Error creating deposit transaction: '.$e->getMessage(), [
                'data' => $data,
                'wallet_id' => $wallet->id,
            ]);

            throw new WalletException('An error occurred while creating the offering', $e->getCode(), $e);
        }
    }
}
