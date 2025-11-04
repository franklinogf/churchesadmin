<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\CurrentYear;
use App\Models\TenantUser;
use Illuminate\Support\Facades\DB;

final class CloseYearAction
{
    /**
     * Close the current year and open the next year.
     */
    public function handle(): void
    {
        DB::transaction(function (): void {
            $currentYear = CurrentYear::current();
            $currentYear->update(['is_current' => false]);
            $nextYearNumber = (int) $currentYear->year + 1;

            $nextYear = CurrentYear::query()
                ->where('year', $nextYearNumber)
                ->first();

            if ($nextYear === null) {
                $nextYear = CurrentYear::query()->create([
                    'year' => $nextYearNumber,
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
