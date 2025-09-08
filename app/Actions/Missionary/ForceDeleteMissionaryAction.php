<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Models\Missionary;

final class ForceDeleteMissionaryAction
{
    /**
     * Handle the action.
     */
    public function handle(Missionary $missionary): void
    {
        // Delete the missionary's address if it exists
        if ($missionary->address) {
            $missionary->address->delete();
        }

        $missionary->forceDelete();
    }
}
