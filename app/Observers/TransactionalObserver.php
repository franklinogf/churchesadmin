<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Check;
use App\Models\CurrentYear;
use App\Models\Expense;
use App\Models\Offering;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class TransactionalObserver
{
    public function creating(Offering|Expense|Check $transactionalModel): void
    {
        /** @var TenantUser|User|null $user */
        $user = Auth::user();

        if ($user === null || ! $user instanceof TenantUser) {
            $transactionalModel->fill(['current_year_id' => CurrentYear::latest()->first()?->id]);

            return;
        }
        $transactionalModel->fill(['current_year_id' => $user->current_year_id]);
    }
}
