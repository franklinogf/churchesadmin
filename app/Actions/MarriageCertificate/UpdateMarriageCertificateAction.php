<?php

declare(strict_types=1);

namespace App\Actions\MarriageCertificate;

use App\Models\MarriageCertificate;

final class UpdateMarriageCertificateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(MarriageCertificate $marriageCertificate, array $data): MarriageCertificate
    {
        $marriageCertificate->update($data);

        return $marriageCertificate;
    }
}
