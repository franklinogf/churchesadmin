<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Models\Missionary;

final class DeleteMissionaryAction
{
    public function handle(Missionary $missionary): void
    {
        $missionary->delete();
    }
}
