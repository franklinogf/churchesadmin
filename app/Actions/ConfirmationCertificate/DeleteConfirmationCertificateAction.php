<?php

declare(strict_types=1);

namespace App\Actions\ConfirmationCertificate;

use App\Models\ConfirmationCertificate;

final class DeleteConfirmationCertificateAction
{
    public function handle(ConfirmationCertificate $confirmationCertificate): void
    {
        $confirmationCertificate->delete();
    }
}
