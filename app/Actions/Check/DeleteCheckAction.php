<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Models\Check;
use Illuminate\Support\Facades\DB;

final class DeleteCheckAction
{
    public function handle(Check $check): void
    {
        DB::transaction(function () use ($check): void {
            $check->transaction->forceDelete();
            $check->delete();
            $check->transaction->wallet->refreshBalance();
        });
    }
}
