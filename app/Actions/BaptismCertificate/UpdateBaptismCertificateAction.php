<?php

declare(strict_types=1);

namespace App\Actions\BaptismCertificate;

use App\Models\BaptismCertificate;

final class UpdateBaptismCertificateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(BaptismCertificate $baptismCertificate, array $data): BaptismCertificate
    {
        $baptismCertificate->update($data);

        return $baptismCertificate;
    }
}
