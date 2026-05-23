<?php

declare(strict_types=1);

namespace App\Actions\Negativa;

use App\Models\Negativa;

final class CreateNegativaAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Negativa
    {
        return Negativa::query()->create($data);
    }
}
