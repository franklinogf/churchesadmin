<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Models\Missionary;

final class UpdateMissionaryAction
{
    /**
     * Handle the action.
     *
     * @param  array<string,mixed>  $data
     * @param  array<string,mixed>|null  $address
     */
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
