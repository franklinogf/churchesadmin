<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Models\FollowUp;

final class DeleteFollowUpAction
{
    /**
     * Handle the action.
     */
    public function handle(FollowUp $followUp): void
    {
        $followUp->delete();
    }
}
