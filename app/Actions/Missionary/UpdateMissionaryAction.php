<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Models\Missionary;

final class UpdateMissionaryAction
{
    public function handle(Missionary $missionary, array $data, ?array $address = null): void
    {

        $missionary->update($data);

        if ($address !== null) {
            $missionary->address()->update($address);
        } else {
            $missionary->address()->delete();
        }
    }
}
