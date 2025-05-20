<?php

declare(strict_types=1);

namespace App\Actions\Offering;

use App\Actions\Wallet\WalletDepositAction;
use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Models\Offering;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class CreateOfferingAction
{
    public function __construct(
        private WalletDepositAction $walletDepositAction,
    ) {}

    /**
     * Handle the creation of an offering.
     *
     * @param  array{wallet_id:string,note?:string|null, date:string, donor_id?:string|null, amount:string, payment_method:string, offering_type:array{id:string, model:string}}  $data
     */
    public function handle(array $data): Offering
    {
        $wallet = ChurchWallet::find($data['wallet_id']);

        if ($wallet === null) {
            throw WalletException::notFound();
        }

        try {
            return DB::transaction(function () use ($data, $wallet) {
                $transaction = $this->walletDepositAction->handle($wallet,
                    new TransactionDto(
                        amount: $data['amount'],
                        meta: new TransactionMetaDto(
                            type: TransactionMetaType::OFFERING
                        )
                    ));

                return Offering::create([
                    'transaction_id' => $transaction->id,
                    'date' => $data['date'],
                    'payment_method' => $data['payment_method'],
                    'note' => $data['note'] ?? null,
                    'donor_id' => $data['donor_id'] ?? null,
                    'offering_type_id' => $data['offering_type']['id'],
                    'offering_type_type' => $data['offering_type']['model'],
                ]);
            });
        } catch (QueryException $e) {
            Log::error('Error creating offering transaction: '.$e->getMessage(), [
                'data' => $data,
                'wallet_id' => $wallet->id,
            ]);

            throw new WalletException('An error occurred while creating the offering', $e->getCode(), $e);
        }
    }
}
