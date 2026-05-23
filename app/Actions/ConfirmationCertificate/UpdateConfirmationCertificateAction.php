<?php

declare(strict_types=1);

namespace App\Actions\ConfirmationCertificate;

use App\Models\ConfirmationCertificate;

final class UpdateConfirmationCertificateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(ConfirmationCertificate $confirmationCertificate, array $data): ConfirmationCertificate
    {
        $confirmationCertificate->update($data);

        return $confirmationCertificate;
    }
}
