<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Exceptions\WalletException;
use App\Models\Check;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Models\Wallet;
use Bavix\Wallet\Services\FormatterService;
use Illuminate\Support\Facades\DB;

final class UpdateCheckAction
{
    public function __construct(
        private readonly FormatterService $formatterService,
    ) {}

    /**
     * handle the update of a check.
     *
     * @param  array{amount?:string,member_id?:string,date?:string,type?:string,confirmed?:bool}  $data
     * @return Check
     */
    public function handle(Check $check, array $data, ?Wallet $wallet = null): Check
    {
        try {
            DB::transaction(function () use ($check, $data, $wallet): void {
                $oldTransaction = $check->transaction;

                if (! $wallet instanceof Wallet) {
                    $wallet = $oldTransaction->wallet;
                }

                if ($oldTransaction->wallet->id !== $wallet->id) {
                    $oldTransaction->forceDelete();
                    $oldTransaction->wallet->refreshBalance();
                    $newTransaction = $wallet->withdrawFloat($data['amount'] ?? $oldTransaction->amount, confirmed: $data['confirmed'] ?? $oldTransaction->confirmed);
                    $check->update(['transaction_id' => $newTransaction->id]);

                } else {
                    $oldTransaction->update([
                        'amount' => $data['amount'] ? $this->formatterService->intValue($data['amount'], 2) : $oldTransaction->amount,
                        'confirmed' => $data['confirmed'] ?? $oldTransaction->confirmed,
                    ]);
                    $oldTransaction->wallet->refreshBalance();
                }

                $check->update([
                    'member_id' => $data['member_id'] ?? $check->member_id,
                    'date' => $data['date'] ?? $check->date,
                    'type' => $data['type'] ?? $check->type,
                ]);
            });

            return $check->refresh();
        } catch (InsufficientFunds) {
            throw new WalletException(__('flash.message.insufficient_funds', [
                'wallet' => $wallet->name,
            ]));
        } catch (BalanceIsEmpty) {
            throw new WalletException(__('flash.message.empty_balance', [
                'wallet' => $wallet->name,
            ]));
        }

    }
}
