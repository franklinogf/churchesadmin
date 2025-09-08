<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Enums\TransactionType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Support\ArrayFallback;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class UpdateWalletAction
{
    public function __construct(
        private WalletDepositAction $walletDepositAction,
        private UpdateTransactionAction $updateTransactionAction,
        private DeleteTransactionAction $deleteTransactionAction,
    ) {}

    /**
     * handle the wallet update action.

     *
     * @param array{
     * balance?:string|null,
     * name?:string,
     * description?:string|null,
     * bank_name?:string,
     * bank_routing_number?:string,
     * bank_account_number?:string,
     * check_layout_id?:int|null,
     * } $data
     */
    public function handle(ChurchWallet $wallet, array $data): ChurchWallet
    {
        try {
            return DB::transaction(function () use ($wallet, $data): ChurchWallet {
                $wallet->update(
                    [
                        'name' => $data['name'] ?? $wallet->name,
                        'description' => ArrayFallback::inputOrFallback($data, 'description', $wallet->description),
                        'bank_name' => $data['bank_name'] ?? $wallet->bank_name,
                        'bank_routing_number' => $data['bank_routing_number'] ?? $wallet->bank_routing_number,
                        'bank_account_number' => $data['bank_account_number'] ?? $wallet->bank_account_number,
                        'check_layout_id' => ArrayFallback::inputOrFallback($data, 'check_layout_id', $wallet->check_layout_id),
                    ]
                );

                if (array_key_exists('balance', $data) && $data['balance'] !== null) {
                    $balance = $data['balance'];
                    $transaction = $wallet->initialTransaction;
                    if ($transaction instanceof Transaction) {
                        $this->updateTransactionAction->handle(
                            $transaction,
                            new TransactionDto(
                                amount: $balance,
                                meta: new TransactionMetaDto(
                                    type: TransactionMetaType::INITIAL,
                                )
                            ),
                            TransactionType::DEPOSIT,
                            $wallet
                        );
                    } else {
                        $this->walletDepositAction->handle($wallet, new TransactionDto(
                            amount: $balance,
                            meta: new TransactionMetaDto(
                                type: TransactionMetaType::INITIAL,
                            )
                        ));
                    }

                } elseif (array_key_exists('balance', $data) && $data['balance'] === null) {
                    $transaction = $wallet->initialTransaction;
                    if ($transaction instanceof Transaction) {
                        $this->deleteTransactionAction->handle($transaction);
                    }
                }

                return $wallet->refresh();
            });
        } catch (QueryException $e) {
            Log::error('Error updating wallet: '.$e->getMessage(), [
                'data' => $data,
                'wallet_id' => $wallet->id,
            ]);
            throw new WalletException('An error occurred while updating the wallet', $e->getCode(), $e);
        }

    }
}
