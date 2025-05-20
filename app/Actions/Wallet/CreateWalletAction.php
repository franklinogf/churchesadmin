<?php

declare(strict_types=1);

namespace App\Actions\Wallet;

use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Exceptions\WalletException;
use App\Models\ChurchWallet;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class CreateWalletAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private WalletDepositAction $walletDepositAction
    ) {}

    /**
     * handle the wallet create action.
     *
     * @param  array{
     * balance?:string|null,
     * name:string,
     * description?:string|null,
     * bank_name:string,
     * bank_routing_number:string,
     * bank_account_number:string,
     * check_layout_id?:int|null } $data
     */
    public function handle(array $data): ChurchWallet
    {
        try {
            return DB::transaction(function () use ($data): ChurchWallet {
                $wallet = ChurchWallet::create([
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'bank_name' => $data['bank_name'],
                    'bank_routing_number' => $data['bank_routing_number'],
                    'bank_account_number' => $data['bank_account_number'],
                    'slug' => str($data['name'])->slug(),
                    'check_layout_id' => $data['check_layout_id'] ?? null,
                ]);

                $balance = $data['balance'] ?? null;

                if ($balance !== null) {

                    $this->walletDepositAction->handle($wallet, new TransactionDto(
                        amount: $balance,
                        meta: new TransactionMetaDto(
                            type: TransactionMetaType::INITIAL,
                        ),
                        confirmed: true,
                    ));

                }

                return $wallet;
            });
        } catch (QueryException $e) {
            Log::error('Error creating wallet: '.$e->getMessage(), [
                'data' => $data,
            ]);
            throw new WalletException('An error occurred while creating the wallet', $e->getCode(), $e);
        }

    }
}
