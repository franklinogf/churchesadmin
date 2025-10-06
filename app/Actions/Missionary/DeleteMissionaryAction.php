<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Models\Missionary;

final class DeleteMissionaryAction
{
    /**
     * Handle the action.
     */
    public function handle(Missionary $missionary): void
    {
        $missionary->delete();
        activity('missionary')
            ->event('deleted')
            ->performedOn($missionary)
            ->log('Missionary :subject.name deleted');
    }
}
