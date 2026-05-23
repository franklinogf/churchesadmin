<?php

declare(strict_types=1);

namespace App\Actions\Negativa;

use App\Models\Negativa;

final class UpdateNegativaAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Negativa $negativa, array $data): Negativa
    {
        $negativa->update($data);

        return $negativa;
    }
}
