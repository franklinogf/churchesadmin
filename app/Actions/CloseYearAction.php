<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\CurrentYear;
use App\Models\TenantUser;
use Illuminate\Support\Facades\DB;

final class CloseYearAction
{
    public function handle(): void
    {
        DB::transaction(function (): void {
            $currentYear = CurrentYear::current();
            $currentYear->update(['is_current' => false]);

            $nextYear = CurrentYear::query()
                ->where('year', $currentYear->year + 1)
                ->first();

            if ($nextYear === null) {
                $nextYear = CurrentYear::query()->create([
                    'year' => $currentYear->year + 1,
                    'start_date' => now(),
                    'end_date' => now()->endOfYear(),
                    'is_current' => true,
                ]);

            } else {
                $nextYear->update(['is_current' => true]);
            }

            TenantUser::query()
                ->update(['current_year_id' => $nextYear->id]);

        });
    }
}
