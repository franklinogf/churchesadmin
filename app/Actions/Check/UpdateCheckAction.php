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
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class UpdateCheckAction
{
    public function __construct(
        private readonly UpdateTransactionAction $updateTransactionAction,
    ) {}

    /**
     * handle the update of a check.
     *
     * @param  array{amount?:string,member_id?:string,date?:string,type?:string,confirmed?:bool}  $data
     * @return Check
     *
     * @throws WalletException
     */
    public function handle(Check $check, array $data, ?Wallet $wallet = null): Check
    {
        try {
            DB::transaction(function () use ($check, $data, $wallet): void {

                $transaction = $this->updateTransactionAction
                    ->handle($check->transaction, new TransactionDto(
                        amount: $data['amount'] ?? $check->transaction->amountFloat,
                        meta: new TransactionMetaDto(
                            type: TransactionMetaType::tryFrom($data['type']) ?? TransactionMetaType::CHECK,
                        ),
                        confirmed: $data['confirmed'] ?? true,
                    ),
                        TransactionType::WITHDRAW,
                        $wallet);

                $check->update([
                    'transaction_id' => $transaction->id,
                    'member_id' => $data['member_id'] ?? $check->member_id,
                    'date' => $data['date'] ?? $check->date,
                    'type' => $data['type'] ?? $check->type,
                ]);
            });

            return $check->refresh();
        } catch (InsufficientFunds) {
            throw WalletException::insufficientFunds($wallet->name ?? 'unknown');
        } catch (BalanceIsEmpty) {
            throw WalletException::emptyBalance($wallet->name ?? 'unknown');
        } catch (Exception $e) {
            Log::error('Error updating check: '.$e->getMessage(), [
                'check_id' => $check->id,
                'data' => $data,
                'wallet_id' => $wallet?->id,
            ]);

            throw new WalletException('An error occurred while updating the check', $e->getCode(), $e);
        }

    }
}
