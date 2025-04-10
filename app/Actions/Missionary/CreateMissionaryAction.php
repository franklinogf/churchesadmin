<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Models\Missionary;

final class CreateMissionaryAction
{
    public function handle(array $data, ?array $address = null): void
    {
        $missionary = Missionary::create($data);

        if ($address !== null) {
            $missionary->address()->create($address);
        }
    }
}
