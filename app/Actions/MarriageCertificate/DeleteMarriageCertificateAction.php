<?php

declare(strict_types=1);

namespace App\Actions\MarriageCertificate;

use App\Models\MarriageCertificate;

final class DeleteMarriageCertificateAction
{
    public function handle(MarriageCertificate $marriageCertificate): void
    {
        $marriageCertificate->delete();
    }
}
