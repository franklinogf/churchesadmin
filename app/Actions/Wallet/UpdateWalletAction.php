<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use App\Support\ArrayFallback;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Services\FormatterService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class UpdateWalletAction
{
    public function __construct(
        private FormatterService $formatterService,
        private WalletDepositAction $walletDepositAction,
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
     * } $data
     * @return ChurchWallet
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
                    ]
                );

                $balance = $data['balance'] ?? null;

                if ($balance !== null) {

                    $transaction = $wallet->transactions()
                        ->where('meta->type', TransactionMetaType::INITIAL->value)
                        ->firstOr(
                            fn (): Transaction => $this->walletDepositAction->handle($wallet, new TransactionDto(
                                amount: $balance,
                                meta: new TransactionMetaDto(
                                    type: TransactionMetaType::INITIAL,
                                )
                            ))

                        );

                    if ($transaction->amountFloat !== $balance) {
                        $transaction->update(['amount' => $this->formatterService->intValue($balance, 2)]);
                        $transaction->wallet->refreshBalance();
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
