<?php

declare(strict_types=1);

namespace App\Actions\Negativa;

use App\Models\Negativa;

final class DeleteNegativaAction
{
    public function handle(Negativa $negativa): void
    {
        $negativa->delete();
    }
}
