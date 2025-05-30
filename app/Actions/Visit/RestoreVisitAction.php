<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Models\Visit;

final class RestoreVisitAction
{
    /**
     * Handle the action.
     */
    public function handle(Visit $visit): void
    {
        $visit->restore();
    }
}
