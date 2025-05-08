<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Enums\TransactionType;
use App\Exceptions\WalletException;
use App\Models\Check;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Models\Wallet;
use Bavix\Wallet\Services\FormatterService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class UpdateCheckAction
{
    public function __construct(
        private FormatterService $formatterService,
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

                    // $oldTransaction->wallet->refreshBalance();
                    $newTransaction = $wallet->withdrawFloat(
                        $data['amount'] ?? $oldTransaction->amount,
                        ['type' => TransactionType::CHECK->value],
                        $data['confirmed'] ?? $oldTransaction->confirmed
                    );
                    $check->update(['transaction_id' => $newTransaction->id]);
                    $oldTransaction->forceDelete();
                    $oldTransaction->wallet->refreshBalance();

                } else {
                    if (isset($data['amount']) && ($oldTransaction->amountFloat !== '-'.$data['amount'])) {

                        $newTransaction = $wallet->withdrawFloat(
                            $data['amount'] ?? $oldTransaction->amount,
                            ['type' => TransactionType::CHECK->value],
                            $data['confirmed'] ?? $oldTransaction->confirmed
                        );
                        $oldTransaction->wallet->refreshBalance();
                        $check->update(['transaction_id' => $newTransaction->id]);
                        $oldTransaction->forceDelete();
                    } else {
                        $oldTransaction->update([
                            'confirmed' => $data['confirmed'] ?? $oldTransaction->confirmed,
                        ]);
                        $oldTransaction->wallet->refreshBalance();
                    }
                }

                $check->update([
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

            throw new Exception('An error occurred while updating the check');
        }

    }
}
