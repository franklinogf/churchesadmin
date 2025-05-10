<?php

declare(strict_types=1);

namespace App\Actions\Offering;

use App\Exceptions\WalletException;
use App\Models\Offering;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class DeleteOfferingAction
{
    /**
     * Handle the deletion of an offering.
     *
     * @return void
     */
    public function handle(Offering $offering): void
    {
        try {
            DB::transaction(function () use ($offering) {
                $wallet = $offering->transaction->wallet;
                $offering->delete();
                $offering->transaction->forceDelete();
                $wallet->refreshBalance();
            });
        } catch (QueryException $e) {
            Log::error('Error deleting offering: '.$e->getMessage(), [
                'offering_id' => $offering->id,
                'wallet_id' => $offering->transaction->wallet->id,
            ]);

            throw new WalletException('An error occurred while deleting the offering');
        }
    }
}
