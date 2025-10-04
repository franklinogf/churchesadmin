<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Models\Missionary;

final class RestoreMissionaryAction
{
    /**
     * Handle the action.
     */
    public function handle(Missionary $missionary): void
    {
        $missionary->restore();
        activity('missionary')
            ->event('restored')
            ->performedOn($missionary)
            ->log('Missionary :subject.name restored');
    }
}
