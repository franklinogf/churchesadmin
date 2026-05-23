<?php

declare(strict_types=1);

namespace App\Actions\FirstCommunionCertificate;

use App\Models\FirstCommunionCertificate;

final class UpdateFirstCommunionCertificateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(FirstCommunionCertificate $firstCommunionCertificate, array $data): FirstCommunionCertificate
    {
        $firstCommunionCertificate->update($data);

        return $firstCommunionCertificate;
    }
}
